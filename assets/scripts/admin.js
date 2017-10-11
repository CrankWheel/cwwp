// import external dependencies
import 'jquery';

// import local dependencies
import Router from './util/Router';
import toplevelPageCrankwheel from './routes/common';

/** Populate Router instance with DOM routes */
const routes = new Router({
	toplevelPageCrankwheel,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());
