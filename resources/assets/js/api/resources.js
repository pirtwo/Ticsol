
/**
 * API base url
 */
export const BASE_URL = 'https://server.dev';


//#region Auth
/**
 * Method: POST
 */
export const LOGIN_URL = BASE_URL + '/api/login';

/**
 * Method: POST
 */
export const REGISTER_URL = BASE_URL + '/api/register';

/**
 * Method: POST
 */
export const LOGOUT_URL = BASE_URL + '/api/logout';

/**
 * Method: POST
 */
export const PASSWORD_RESET_URL = BASE_URL + '/api/passwordreset';

//#endregion

//#region Jobs

/**
 * Method: GET.
 */
export const JOB_LIST_URL = BASE_URL + '/api/jobs/list';

//#endregion

//#region Jobs

/**
 * Method: GET.
 */
export const USER_LIST_URL = BASE_URL + '/api/user/list';

//#endregion
