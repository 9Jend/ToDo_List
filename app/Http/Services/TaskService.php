<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\Tag;
use Illuminate\Http\UploadedFile;

class TaskService
{
    public function store($data)
    {
        if (array_key_exists('tags', $data)) {
            $tags = $this->tagsToArray($data['tags']);
            unset($data['tags']);
        }

        if (isset($data['photo'])) {
            $data['photo'] = $this->uploadFile($data['photo'], $data['task_list_id']);
        }

        $task = Task::create($data);

        $this->addTags($tags, $task->id);

        return $data['task_list_id'];
    }

    public function update(TaskList $taskList, Task $task, $data)
    {
        $removePhoto = null;

        if (array_key_exists('tags', $data)) {
            $tags = $this->tagsToArray($data['tags']);
            $task->tags()->delete();
            $this->addTags($tags, $task->id);
            unset($data['tags']);
        }

        if (array_key_exists('removePhoto', $data)) {
            $removePhoto = $data['removePhoto'];
            unset($data['removePhoto']);
        }

        if (isset($data['photo'])) {
            $data['photo'] = $this->uploadFile($data['photo'], $taskList->id);

            if ($task->photo && $data['photo'])
                @unlink(public_path() . $task->photo);
        } elseif ($removePhoto && $task->photo) {
            @unlink(public_path() . $task->photo);
            $data['photo'] = null;
        }

        $task->update($data);

        return $data['photo'] ?? null;
    }

    private function uploadFile(UploadedFile $photo, $taskListId): string
    {
        $pathToPhoto = null;
        if ($photo->isFile()) {
            $path = '/taskImage/' . $taskListId;
            $fileName = uniqid() . $photo->getClientOriginalName();
            if ($photo->move(public_path() . $path, $fileName))
                $pathToPhoto = $path . '/' . $fileName;
        }

        return $pathToPhoto;
    }

    private function addTags($tags, $taskId): void
    {
        foreach ($tags as $tag) {
            if (!empty($tag)) {
                Tag::create([
                    'task_id'   => $taskId,
                    'name'      => $tag,
                ]);
            }
        }
    }

    private function tagsToArray($tags): array
    {
        return preg_split('/[^a-zA-Z0-9]+/', $tags);
    }
}
