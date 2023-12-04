<?php

namespace Cmf\Upload\Api\Controllers;

use Cmf\Api\Controller\AbstractListController;
use Cmf\Http\UrlGenerator;
use Cmf\Upload\Api\Serializers\FileSerializer;
use Cmf\Upload\File;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUploadsController extends AbstractListController
{
    public $serializer = FileSerializer::class;

    public $sortFields = ['id'];

    /**
     * @var UrlGenerator
     */
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $params = $request->getQueryParams();

        // User is signed in
        $actor->assertRegistered();

        $filterByUserId = Arr::get($params, 'filter.user', $actor->id);

        // Can this user load other their files?
        if (intval($filterByUserId) !== $actor->id) {
            $actor->assertCan('cmf-upload.viewUserUploads');
        }

        // Params
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        // Build query
        $query = File::query()
            ->where('actor_id', $filterByUserId)
            ->where('hide_from_media_manager', false);

        $results = $query
            ->skip($offset)
            ->take($limit + 1)
            ->orderBy('id', 'desc')
            ->get();

        // Check for more results
        $hasMoreResults = $limit > 0 && $results->count() > $limit;

        // Pop
        if ($hasMoreResults) {
            $results->pop();
        }

        // Add pagination to the request
        $document->addPaginationLinks(
            $this->url->to('api')->route('cmf-upload.list'),
            $params,
            $offset,
            $limit,
            $hasMoreResults ? null : 0
        );

        return $results;
    }
}
