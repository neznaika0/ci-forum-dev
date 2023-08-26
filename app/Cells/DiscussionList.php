<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\ThreadModel;

class DiscussionList extends Cell
{
    protected string $view = 'discussion_list_cell';

    public array $threads = [];
    public string $listType;
    public $pager;

    public function mount()
    {
        $threadModel = model(ThreadModel::class);
        $this->listType = request()->getVar('type') ?? 'recent-posts';

        $this->threads = $threadModel->forList([
            'type' => $this->listType,
        ]);
        $this->pager = $threadModel->pager;
    }
}
