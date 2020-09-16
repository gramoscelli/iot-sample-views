// urlParams is null when used for embedding
window.urlParams = window.urlParams || {};

// Public global variables
window.MAX_REQUEST_SIZE = window.MAX_REQUEST_SIZE  || 10485760;
window.MAX_AREA = window.MAX_AREA || 15000 * 15000;

// URLs for save and export
window.EXPORT_URL = '/export';
window.SAVE_URL = '/save';
window.OPEN_URL = '/open';
window.RESOURCES_PATH = '/resources';
window.RESOURCE_BASE = '/resources/grapheditor';
window.STENCIL_PATH = '/stencils';
window.IMAGE_PATH = '/images';
window.STYLE_PATH = '/css';
window.CSS_PATH = '/styles';
window.OPEN_FORM = '/open.html';

// Sets the base path, the UI language via URL param and configures the
// supported languages to avoid 404s. The loading of all core language
// resources is disabled as all required resources are in grapheditor.
// properties. Note that in this example the loading of two resource
// files (the special bundle and the default bundle) is disabled to
// save a GET request. This requires that all resources be present in
// each properties file since only one file is loaded.
window.mxBasePath = '/src';
window.mxLanguage = '';
window.mxLanguages = ['de'];
