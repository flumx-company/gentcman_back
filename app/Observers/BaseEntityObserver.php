<?php

namespace Gentcmen\Observers;

use Gentcmen\Models\User;
use Gentcmen\Notifications\EntityManipulationNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class BaseEntityObserver
{
    /**
     * Handle the Model "created" event.
     *
     * @param Model $model
     * @return void
     */

    public function created(Model $model)
    {
        $this->notifyAdmins($model, 'created');
    }

    /**
     * Handle the Model "updated" event.
     *
     * @param Model $model
     * @return void
     */

    public function updated(Model $model)
    {
        $this->notifyAdmins($model, 'updated');
    }

    /**
     * Handle the Model "deleted" event.
     *
     * @param Model $model
     * @return void
     */

    public function deleted(Model $model)
    {
        $this->notifyAdmins($model, 'deleted');
    }

    /**
     * Handle the Model "forceDeleted" event.
     *
     * @param Model $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        $this->notifyAdmins($model, 'forceDeleted');
    }

    /**
     * Notify admins
     * @param $model
     * @param $type
     */

    private function notifyAdmins($model, $type)
    {
        Notification::send(User::fetchAdmins(), new EntityManipulationNotification((object) [
            'type' => $type,
            'modelName' => $this->getModelName($model),
        ]));
    }

    /**
     * Get model name
     * @param Model $model
     * @return string|null
     */

    private function getModelName(Model $model): ?string
    {
        $exploded = explode('\\', $model::class);
        return array_pop($exploded);
    }
}
