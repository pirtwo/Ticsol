
/**
 * API base url
 */
export const BASE_URL = 'https://server.dev/api';


//#region Auth
/**
 * Method: POST
 */
export const AUTH_LOGIN = BASE_URL + '/login';

/**
 * Method: POST
 */
export const AUTH_REGISTER = BASE_URL + '/register';

/**
 * Method: POST
 */
export const AUTH_LOGOUT = BASE_URL + '/logout';

/**
 * Method: POST
 */
export const AUTH_PASSWORD_RESET = BASE_URL + '/passwordreset';

//#endregion

//#region Jobs

/**
 * Method: GET.
 */
export const JOB_LIST = BASE_URL + '/job/list';

/**
 * Method: POST.
 */
export const JOB_CREATE = BASE_URL + '/job/create';

/**
 * Method: POST.
 */
export const JOB_UPDATE = BASE_URL + '/job/update';

//#endregion

//#region Users

/**
 * Method: GET.
 */
export const USER_LIST = BASE_URL + '/user/list';

//#endregion


export const SCHEDULE_LIST = BASE_URL + '/schedule/list';
export const SCHEDULE_CREATE = BASE_URL + '/schedule/create';
export const SCHEDULE_UPDATE = BASE_URL + '/schedule/update';
export const SCHEDULE_DELETE = BASE_URL + '/schedule/delete';
