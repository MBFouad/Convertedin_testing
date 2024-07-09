<?php

namespace App\Http\Controllers;

use App\Exceptions\PaginatorWrongPageNumber;
use App\Http\Requests\TaskRequest;
use App\Models\FileType;
use App\Models\Task;
use App\Models\TasksCountView;
use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @param Request $request
     *
     * @return Renderable|RedirectResponse
     */
    public function tasks(Request $request)
    {
        $qb = $this->createQueryFromRequest($request);

        try {
            $paginatorData = $this->getPaginator(
                $qb,
                $request,
                [
                    'id' => 'tasks.id',
                    'title' => 'tasks.title',
                    'description' => 'tasks.description',
                    'assign_to' => 'assignTo.name',
                    'created_by' => 'createdBy.name',
                    'created_at' => 'tasks.created_at',
                ],
                [
                    'tasks.id',
                    'tasks.title',
                    'tasks.description',
                    'assignTo.name',
                    'createdBy.name',
                    'tasks.created_at',
                ]
            );
            return view('tasks.index', [
                    'tasks' => $paginatorData['paginator'],
                ] + $paginatorData['params']);
        } catch (PaginatorWrongPageNumber $e) {
            return redirect(url()->current());
        }

        return view('home.index', compact('registration_token'));
    }

    protected function createQueryFromRequest(Request $request): Builder
    {
        $qb = Task::query()
            ->select(['tasks.*'])
            ->select(DB::raw("tasks.id, tasks.title, tasks.assign_to, tasks.created_by, tasks.created_at, assignTo.name as assignTo, createdBy.name as createdBy,
        IF(CHAR_LENGTH(tasks.description) - CHAR_LENGTH(REPLACE(tasks.description, ' ', '')) >= 20,
        CONCAT(SUBSTRING_INDEX(tasks.description, ' ', 20), '...'),
        tasks.description) as description"))
            ->join('users as assignTo', 'tasks.assign_to', '=', 'assignTo.id')
            ->join('users as createdBy', 'tasks.created_by', '=', 'createdBy.id');
//            ->with('assignTo', 'createdBy');
        if (!auth()->user()->hasRole(Constants::USER_ROLE['Admin'])) {
            $qb->where('assign_to', auth()->id());
        }

        return $qb;
    }

    public function create()
    {
        $users = User::all()->pluck('name', 'id');
        $admins = User::role(Constants::USER_ROLE['Admin'])->pluck('name', 'id');

        return view('tasks.create', compact('users', 'admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FileRequest $request
     *
     * @return string
     */
    public function store(TaskRequest $request): string
    {
        $entity = Task::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'assign_to' => $request->get('assign_to'),
            'created_by' => $request->get('created_by'),
        ]);

        return trans('tasks.titles.created');
    }

    public function statistics()
    {
        $topUsers = TasksCountView::orderBy('tasks_count', 'desc')
            ->with('assignTo')
            ->take(10)
            ->get();
        return view('tasks.statistics', compact('topUsers'));

    }

}
