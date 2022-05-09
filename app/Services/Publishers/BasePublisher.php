<?php

namespace App\Services\Publishers;

use App\Models\Album;
use App\Models\AlbumReleaseLog;
use App\Models\AlbumSubmission;

abstract class BasePublisher
{
    public $album;

    public $releaseLogs = [];

    public $publisherType;

    /**
     * @param Album $album
     */
    public function __construct(Album $album)
    {
        $this->album = $album;

        $this->setPublisherType();
    }

    /**
     * Publish the album.
     *
     * @return mixed
     */
    abstract public function publish();

    /**
     * Set publisher type
     *
     * @return void
     */
    abstract public function setPublisherType(): void;

    /**
     * Pushes new entries to release logs
     *
     * @param string $stepName
     * @param bool $stepStatus
     * @param string $stepMessage
     * @param array $extraContent
     */
    public function log(string $stepName, bool $stepStatus, string $stepMessage, array $extraContent = [])
    {
        array_push($this->releaseLogs, [
            "STEP_NAME" => $stepName,
            "STEP_STATUS" => $stepStatus,
            "STEP_MESSAGE" => $stepMessage,
            "STEP_EXTRA_CONTENT" => $extraContent,
        ]);
    }

    /**
     * Saves album release log to db
     *
     * @return AlbumSubmission
     */
    public function saveAlbumSubmission($status = AlbumSubmission::PUBLISH_STATUS_PENDING): AlbumSubmission
    {
        return AlbumSubmission::create([
            'album_id' => $this->album->id,
            'publisher' => $this->publisherType,
            'publisher_album_id' => $this->album->fuga_product_id,
            'status' => $status,
            'logs' => $this->releaseLogs
        ]);
    }
}
