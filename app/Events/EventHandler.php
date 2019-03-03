<?php namespace App\Events;

use App\Handlers\Events\Page;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\SerializesModels;

class EventHandler extends Event {

	use SerializesModels;

    /**
     * Create a new event instance.
     *
     */
	public function __construct()
	{
		//
	}

	/**
     * Handle user login events.
     */
    public function onBeforePageLoad($page)
    {
        return $page;
    }

	/**
     * Handle user login events.
     */
    public function onAfterPageLoad($page)
    {
        //
    }

	/**
     * Handle user login events.
     */
    public function onGlobalsLoaded($globals)
    {
        return $globals;
    }

	/**
     * Handle user login events.
     */
    public function onBeforeAJAXResponse($data)
    {
        $p = new Page;
        $data = $p->onBeforeAJAXResponse($data);

        return $data;
    }

	/**
     * Handle user login events.
     */
    public function onAfterAJAXResponse($data)
    {
        return $data;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('BeforePageLoad', 'App\Events\EventHandler@onBeforePageLoad');

        $events->listen('AfterPageLoad', 'App\Events\EventHandler@onAfterPageLoad');

        $events->listen('GlobalsLoaded', 'App\Events\EventHandler@onGlobalsLoaded');

        $events->listen('BeforeAJAXResponse', 'App\Events\EventHandler@onBeforeAJAXResponse');

        $events->listen('AfterAJAXResponse', 'App\Events\EventHandler@onAfterAJAXResponse');
    }

}
