<?php
/**
 * @package MyBB 1.8
 * @author MyBB Group
 * @license Copyright 2014 MyBB Group, All Rights Reserved. See http://www.mybb.com/about/license *
 * Website: //www.mybb.com
 * License: //www.mybb.com/about/license
 */

function task_threadviews($task)
{
	global $mybb, $db, $lang, $plugins;

	if($mybb->settings['delayedthreadviews'] != 1)
	{
		return;
	}

	// Update thread views
	$query = $db->simple_select("threadviews", "tid, COUNT(tid) AS views", "", array('group_by' => 'tid'));
	while($threadview = $db->fetch_array($query))
	{
		$db->update_query("threads", array('views' => "views+{$threadview['views']}"), "tid='{$threadview['tid']}'", 1, TRUE);
	}

	$db->write_query("TRUNCATE TABLE ".TABLE_PREFIX."threadviews");

	if(is_object($plugins))
	{
		$plugins->run_hooks('task_threadviews', $task);
	}

	add_task_log($task, $lang->task_threadviews_ran);
}
