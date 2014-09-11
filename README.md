cets-multisite-dashboard-crawler
================================

// A WordPress plugin to hit the dashboard of every site in a MultiSite network


This plugin is designed to address an issue regarding updating plugins in a MultiSite environment.  If a plugin update needs to perform any database updates or other maintenance tasks, doing so in a MultiSite environment can lead to large problems.  This plugin addresses this by providing a utility to touch the backend of every site in a MultiSite environment, thus allowing the updated plugin to perform any required updates.  
<br/>For a more thorough description of the problem, see my ticket on Trac:  https://core.trac.wordpress.org/ticket/28694


To Use This Plugin:<br/>
Network Activate it.<br/>
From your Network Admin, click on Settings -> Crawl Dashboards
