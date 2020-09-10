/**
 * @apiDefine DefaultRequest
 * This is default microservice request format.
 *
 * @apiVersion 1.0.0
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *     	 "jsonrpc": "2.0",
 *     	 "id": 1,
 *     	 "method": "See method above",
 *       "params": "See params section above"
 *     }
 */

/**
 * @apiDefine DefaultApiError
 * This is default microservice error response.
 *
 * @apiVersion 1.0.0
 *
 * @apiError (Error 200) {Number} code Error code error.
 * @apiError (Error 200) {Number} status Status error flag.
 * @apiError (Error 200) {String} service Service where the error occurred.
 * @apiError (Error 200) {String} message Error message.
 *
 * @apiErrorExample Error-Response:
 *     {
 *     	 "jsonrpc": "2.0",
 *       "error": {
 *           "code": 10,
 *           "status": 1,
 *           "service": "projectAlias.geo",
 *           "message": "Endpoint exception (controller.action): message"
 *       }
 *     }
 */

/**
 * @apiDefine DefaultApiSuccess
 * This is default microservice success response.
 *
 * @apiVersion 1.0.0
 *
 * @apiErrorExample Success-Response:
 *     {
 *     	 "jsonrpc": "2.0",
 *     	 "id": 1,
 *       "result": "See success 200 section"
 *     }
 */

/**
 * @apiDefine DefaultViewResponse
 * Default view response
 *
 * @apiSuccess {Object} model Model.
 *
 * @apiVersion 1.0.0
 */

/**
 * @apiDefine DefaultUpdateResponse
 * Default update response
 *
 * @apiSuccess {Object} model Model.
 *
 * @apiVersion 1.0.0
 */

/**
 * @apiDefine DefaultListResponse
 * Default list response
 *
 * @apiSuccess {Object[]} list Models.
 * @apiSuccess {[Pagination](#api-TYPES-ObjectPagination)} pagination List pagination.
 *
 * @apiVersion 1.0.0
 */

/**
 * @apiDefine DefaultDeleteResponse
 * Default delete response
 *
 * @apiVersion 1.0.0
 *
 * @apiErrorExample Success-Response:
 *     {
 *     	 "status": "OK"
 *     }
 */

/**
 * @api {Object} Pagination
 * Pagination
 *
 * @apiGroup TYPES
 *
 * @apiParam {Number} totalItems=0 total count items.
 * @apiParam {Number} pageCount=1 count all pages.
 * @apiParam {Number} currentPage=1 current page.
 * @apiParam {Number} perPage=20 page size.
 *
 * @apiVersion 1.0.0
 */

/**
 * @api {Object} JsonQuery
 * JsonQuery
 *
 * @apiGroup TYPES
 *
 * @apiParam {Object} [filter] Where condition
 * @apiParam {String[]} [attributes] Selected attributes. Default: all
 * @apiParam {Number} [page=1] current page
 * @apiParam {Number} [perPage=20] page size
 * @apiParam {Boolean} [allPage=false] get all items without pagination
 * @apiParam {String[]} [orderBy] sorting. E.g.: `["-age", "id"]`
 * @apiParam {String[]} [expands] expands. E.g.: `["pictures", {"name": "pictures", "where": {}}]`
 *
 * @apiVersion 1.0.0
 */
