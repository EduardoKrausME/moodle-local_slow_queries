<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * local_slow_queries.php
 *
 * @package   local_slow_queries
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['col_avgtime'] = 'Avg time (s)';
$string['col_count'] = 'Count';
$string['col_cron'] = 'CRON';
$string['col_exectime'] = 'Time (s)';
$string['col_origin'] = 'Backtrace';
$string['col_parameters'] = 'Parameters';
$string['col_sqlpreview'] = 'SQL';
$string['detail_indexes'] = 'Possible missing indexes';
$string['detail_indexes_none'] = 'No index suggestions detected for this query.';
$string['detail_indexes_notice'] = 'Suggestions are heuristic. Test carefully on a staging environment and validate with EXPLAIN/ANALYZE.';
$string['detail_sql'] = 'SQL and parameters';
$string['detail_sql_expanded'] = 'SQL with parameters';
$string['detail_title'] = 'Query details';
$string['emptytable'] = 'No queries found for the selected filters.';
$string['filter_apply'] = 'Apply';
$string['filter_minexec'] = 'Min exec time (s)';
$string['filter_reset'] = 'Reset';
$string['filter_search'] = 'Search SQL';
$string['filter_search_ph'] = 'Type part of the SQL to search...';
$string['filter_title'] = 'Filters';
$string['index_title'] = 'Slow queries';
$string['logslow_warning_body'] = 'This page reads from <code>mdl_log_queries</code>, but your site is not configured to log slow SQL queries. Enable <code>logslow</code> in <code>config.php</code> (set to <code>true</code> or a number in seconds). Example:';
$string['logslow_warning_current'] = 'Current value';
$string['logslow_warning_hint'] = 'After saving <code>config.php</code>, reproduce the slow page/cron task and then refresh this page to see new entries.';
$string['logslow_warning_title'] = 'Slow query logging is disabled';
$string['nav_index'] = 'Slow queries';
$string['pluginname'] = 'Slow queries';
$string['privacy:metadata'] = 'The Slow queries plugin does not store any personal data. It only displays existing database query log records to administrators.';
$string['slow_queries'] = 'Slow queries';
