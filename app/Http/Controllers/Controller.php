<?php

namespace App\Http\Controllers;


use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="leave API",
 *      description="請假系統相關api",
 * )
 * @OA\PathItem(
 *      path="/"
 *  )
 * 
 * @OA\server(
 *      url = "http://localhost/api",
 *      description="Docker:Localhost"
 * )
 * 
 * @OA\server(
 *      url = "http://localhost:8000/api",
 *      description="Localhost"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="登入登出和個人資訊"
 * )
 * @OA\Tag(
 *     name="Users",
 *     description="新增修改刪除使用者，或是列出使用者資訊，只有管理權限的使用者可以操作"
 * )
 * @OA\Tag(
 *     name="Leave",
 *     description="請假相關動作"
 * )
 * 
 * @OA\Post(
 *     path="/auth/login",
 *     summary="使用者登入",
 *     description="使用者登入",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 example={"email": "admin1@gmail.com","password": "admin1234"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Successfully logged in"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The email field is required. (and 1 more error)",
 *                     "errors": {
 *                         "email": {
 *                              "The email field is required."
 *                          },
 *                          "password": {
 *                              "The password field is required."
 *                          }
 *                     }
 *                }
 *            ),
 *     ),
 * )
 * @OA\Post(
 *     path="/auth/logout",
 *     summary="使用者登出",
 *     description="使用者登出",
 *     tags={"Auth"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Successfully logged out"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 * )
 * @OA\Get(
 *     path="/auth/user",
 *     summary="取得個人資料",
 *     description="取得個人資料",
 *     tags={"Auth"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "id": 1,
 *                     "name": "admin1",
 *                     "email": "admin1@gmail.com",
 *                     "is_admin": 1,
 *                     "profile": null
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 * )
 * @OA\Get(
 *     path="/auth/user/leave",
 *     summary="取得個人請假資訊",
 *     description="取得個人請假資訊",
 *     tags={"Auth"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                   "current_page": 1,
 *                   "data": {
 *                      {
 *                           "id": 1,
 *                           "user_id": 3,
 *                           "leave_id": 1,
 *                           "description": "got some fever symptoms",
 *                           "start_date": "2024-08-11",
 *                           "start_time": "09:30:00",
 *                           "end_date": "2024-08-11",
 *                           "end_time": "18:30:00",
 *                           "is_reviewed": 1,
 *                           "user": {
 *                               "id": 3,
 *                               "name": "test1",
 *                               "email": "test1@gmail.com",
 *                               "is_admin": 0
 *                           },
 *                           "leave": {
 *                               "id": 1,
 *                               "name": "sick leave"
 *                           },
 *                           "result": {
 *                               "id": 1,
 *                               "leave_request_id": 1,
 *                               "review_user_id": 1,
 *                               "is_allowed": 1,
 *                               "user": {
 *                                   "id": 1,
 *                                   "name": "admin1",
 *                                   "email": "admin1@gmail.com",
 *                                   "is_admin": 1
 *                               }
 *                           }
 *                       }
 *                    },
 *                    "first_page_url": "http://localhost:8000/api/auth/user/leave?page=1",
 *                    "from": 1,
 *                    "last_page": 1,
 *                    "last_page_url": "http://localhost:8000/api/auth/user/leave?page=1",
 *                    "links": {
 *                       {
 *                           "url": null,
 *                           "label": "&laquo; Previous",
 *                           "active": false
 *                       },
 *                       {
 *                           "url": "http://localhost:8000/api/auth/user/leave?page=1",
 *                           "label": "1",
 *                           "active": true
 *                       },
 *                       {
 *                           "url": null,
 *                           "label": "Next &raquo;",
 *                           "active": false
 *                       }
 *                    },
 *                    "next_page_url": null,
 *                    "path": "http://localhost:8000/api/auth/user/leave",
 *                    "per_page": 10,
 *                    "prev_page_url": null,
 *                    "to": 2,
 *                    "total": 2
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 * )
 * @OA\Get(
 *     path="/users",
 *     summary="取得全部使用者資料",
 *     description="取得全部使用者資料",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                   "current_page": 1,
 *                   "data": {
 *                       {
 *                           "id": 1,
 *                           "name": "admin1",
 *                           "email": "admin1@gmail.com",
 *                           "is_admin": 1
 *                       },
 *                       {
 *                           "id": 2,
 *                           "name": "test1",
 *                           "email": "test1@gmail.com",
 *                           "is_admin": 0
 *                       }
 *                    },
 *                    "first_page_url": "http://localhost:8000/api/users?page=1",
 *                    "from": 1,
 *                    "last_page": 1,
 *                    "last_page_url": "http://localhost:8000/api/users?page=1",
 *                    "links": {
 *                       {
 *                           "url": null,
 *                           "label": "&laquo; Previous",
 *                           "active": false
 *                       },
 *                       {
 *                           "url": "http://localhost:8000/api/users?page=1",
 *                           "label": "1",
 *                           "active": true
 *                       },
 *                       {
 *                           "url": null,
 *                           "label": "Next &raquo;",
 *                           "active": false
 *                       }
 *                    },
 *                    "next_page_url": null,
 *                    "path": "http://localhost:8000/api/users",
 *                    "per_page": 10,
 *                    "prev_page_url": null,
 *                    "to": 2,
 *                    "total": 2
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 * )
 * @OA\Post(
 *     path="/users",
 *     summary="建立使用者",
 *     description="建立使用者",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                  @OA\Property(
 *                     property="is_admin",
 *                     type="boolean"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password_confirmation",
 *                     type="string"
 *                 ),
 *                 example={"name":"example1","email": "example1@gmail.com","is_admin": false,
 *                          "password": "example1234","password_confirmation" :"example1234"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The name field is required. (and 3 more error)",
 *                     "errors": {
 *                         "name": {
 *                            "The name field is required."
 *                          },
 *                         "email": {
 *                            "The email field is required."
 *                          },
 *                         "is_admin": {
 *                            "The is_admin field is required."
 *                          },
 *                         "password": {
 *                            "The password field is required."
 *                         }
 *                     }
 *                }
 *            ),
 *     ),
 * )
 * @OA\Get(
 *     path="/users/{user_id}",
 *     operationId="getUserById",
 *     summary="取得特定使用者資料",
 *     description="取得特定使用者資料",
 *      @OA\Parameter(
 *          name="user_id",
 *          description="User id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                   "id": 1,
 *                   "name": "admin1",
 *                   "email": "admin1@gmail.com",
 *                   "is_admin": 1,
 *                   "profile": {
 *                       "id": 1,
 *                       "user_id": 1,
 *                       "birthday_date": "1997-07-07",
 *                       "entry_date": "2024-07-07"
 *                   }
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 * )
 * @OA\Delete(
 *     path="/users/{user_id}",
 *     operationId="deleteUserById",
 *     summary="刪除特定使用者",
 *     description="刪除特定使用者",
 *      @OA\Parameter(
 *          name="user_id",
 *          description="User id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Users"},
 *     @OA\Response(
 *         response=204,
 *         description="No Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success",
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 * )
 * @OA\Put(
 *     path="/users/{user_id}/profile",
 *     operationId="putUserById-profile",
 *     summary="修改特定使用者額外資訊",
 *     description="修改特定使用者額外資訊",
 *      @OA\Parameter(
 *          name="user_id",
 *          description="User id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="birthday_date",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="entry_date",
 *                     type="string"
 *                 ),
 *                 example={"birthday_date": "1996-12-25","entry_date": "2024-07-07" }
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The birthday_date field is required. (and 1 more error)",
 *                     "errors": {
 *                         "birthday_date": {
 *                              "The birthday_date field is required."
 *                          },
 *                          "entry_date": {
 *                              "The entry_date field is required."
 *                          }
 *                     }
 *                }
 *            ),
 *     ),
 * )
 * @OA\Put(
 *     path="/users/{user_id}/email",
 *     operationId="putUserById-email",
 *     summary="修改特定使用者郵件信箱",
 *     description="修改特定使用者郵件信箱",
 *      @OA\Parameter(
 *          name="user_id",
 *          description="User id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 example={"email": "test9876@gmail.com" }
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The email field is required.",
 *                     "errors": {
 *                         "email": {
 *                              "The email field is required."
 *                          },
 *                     }
 *                }
 *            ),
 *     ),
 * )
 * @OA\Put(
 *     path="/users/{user_id}/password",
 *     operationId="putUserById-password",
 *     summary="修改特定使用者密碼",
 *     description="修改特定使用者密碼",
 *      @OA\Parameter(
 *          name="user_id",
 *          description="User id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password_confirmation",
 *                     type="string"
 *                 ),
 *                 example={"password": "test9876", "password_confirmation"="test9876"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The password field is required.",
 *                     "errors": {
 *                         "password": {
 *                              "The password field is required."
 *                          },
 *                     }
 *                }
 *            ),
 *     ),
 * )
  * @OA\Get(
 *     path="/users/{user_id}/leave",
 *     operationId="getUserById-leave",
 *     summary="取得特定使用者請假狀態",
 *     description="取得特定使用者請假狀態",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                   "current_page": 1,
 *                   "data": {
 *                      {
 *                           "id": 1,
 *                           "user_id": 3,
 *                           "leave_id": 1,
 *                           "description": "got some fever symptoms",
 *                           "start_date": "2024-08-11",
 *                           "start_time": "09:30:00",
 *                           "end_date": "2024-08-11",
 *                           "end_time": "18:30:00",
 *                           "is_reviewed": 1,
 *                           "user": {
 *                               "id": 3,
 *                               "name": "test1",
 *                               "email": "test1@gmail.com",
 *                               "is_admin": 0
 *                           },
 *                           "leave": {
 *                               "id": 1,
 *                               "name": "sick leave"
 *                           },
 *                           "result": {
 *                               "id": 1,
 *                               "leave_request_id": 1,
 *                               "review_user_id": 1,
 *                               "is_allowed": 1,
 *                               "user": {
 *                                   "id": 1,
 *                                   "name": "admin1",
 *                                   "email": "admin1@gmail.com",
 *                                   "is_admin": 1
 *                               }
 *                           }
 *                       }
 *                    },
 *                    "first_page_url": "http://localhost:8000/api/user/3/leave?page=1",
 *                    "from": 1,
 *                    "last_page": 1,
 *                    "last_page_url": "http://localhost:8000/api/user/3/leave?page=1",
 *                    "links": {
 *                       {
 *                           "url": null,
 *                           "label": "&laquo; Previous",
 *                           "active": false
 *                       },
 *                       {
 *                           "url": "http://localhost:8000/api/user/3/leave?page=1",
 *                           "label": "1",
 *                           "active": true
 *                       },
 *                       {
 *                           "url": null,
 *                           "label": "Next &raquo;",
 *                           "active": false
 *                       }
 *                    },
 *                    "next_page_url": null,
 *                    "path": "http://localhost:8000/api/user/3/leave",
 *                    "per_page": 10,
 *                    "prev_page_url": null,
 *                    "to": 2,
 *                    "total": 2
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 * )
 * @OA\Post(
 *     path="/leave/{leave_id}/request",
 *     operationId="postLeaveById",
 *     summary="建立請假請求",
 *     description="建立請假請求",
 *      @OA\Parameter(
 *          name="leave_id",
 *          description="Leave id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Leave"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="description",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="start_date",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="start_time",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="end_date",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="end_time",
 *                     type="string"
 *                 ),
 *                 example={"description": "got some fever symptoms",
 *                          "start_date":"2024-08-11","start_time":"09:30",
 *                          "end_date":"2024-08-11","end_time":"18:30"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The description field is required. (and 1 more error)",
 *                     "errors": {
 *                         "description": {
 *                              "The description field is required."
 *                          },
 *                         "start_date": {
 *                              "The start_date field is required."
 *                          },
 *                         "start_time": {
 *                              "The start_time field is required."
 *                          },
 *                         "end_date": {
 *                              "The end_date field is required."
 *                          },
 *                         "end_time": {
 *                              "The end_time field is required."
 *                          },
 *                     }
 *                }
 *            ),
 *     ),
 * )
 * @OA\Post(
 *     path="/leaveRequest/{leave_requeset_id}/review",
 *     operationId="postLeaveRequestById",
 *     summary="簽核請假請求(管理權限只用者才可操作)",
 *     description="簽核請假請求(管理權限只用者才可操作)",
 *      @OA\Parameter(
 *          name="leave_requeset_id",
 *          description="Leave Request id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     tags={"Leave"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="is_allowed",
 *                     type="boolean"
 *                 ),
 *                 example={"is_allowed": true}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "Success"
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *                example={
 *                     "message": "The is_allowed field is required.",
 *                     "errors": {
 *                         "is_allowed": {
 *                              "The is_allowed field is required."
 *                          },
 *                     }
 *                }
 *            ),
 *     ),
 * )
 * @OA\Get(
 *     path="/leave",
 *     summary="取得全部請假狀態(管理權限只用者才可操作)",
 *     description="取得全部請假狀態(管理權限只用者才可操作)",
 *     tags={"Leave"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                example={
 *                   "current_page": 1,
 *                   "data": {
 *                      {
 *                           "id": 1,
 *                           "user_id": 3,
 *                           "leave_id": 1,
 *                           "description": "got some fever symptoms",
 *                           "start_date": "2024-08-11",
 *                           "start_time": "09:30:00",
 *                           "end_date": "2024-08-11",
 *                           "end_time": "18:30:00",
 *                           "is_reviewed": 1,
 *                           "user": {
 *                               "id": 3,
 *                               "name": "test1",
 *                               "email": "test1@gmail.com",
 *                               "is_admin": 0
 *                           },
 *                           "leave": {
 *                               "id": 1,
 *                               "name": "sick leave"
 *                           },
 *                           "result": {
 *                               "id": 1,
 *                               "leave_request_id": 1,
 *                               "review_user_id": 1,
 *                               "is_allowed": 1,
 *                               "user": {
 *                                   "id": 1,
 *                                   "name": "admin1",
 *                                   "email": "admin1@gmail.com",
 *                                   "is_admin": 1
 *                               }
 *                           }
 *                       },
 *                       {
 *                           "id": 2,
 *                           "user_id": 4,
 *                           "leave_id": 1,
 *                           "description": "got some fever symptoms",
 *                           "start_date": "2024-08-20",
 *                           "start_time": "09:30:00",
 *                           "end_date": "2024-08-20",
 *                           "end_time": "18:30:00",
 *                           "is_reviewed": 1,
 *                           "user": {
 *                               "id": 4,
 *                               "name": "test2",
 *                               "email": "test2@gmail.com",
 *                               "is_admin": 0
 *                           },
 *                           "leave": {
 *                               "id": 1,
 *                               "name": "sick leave"
 *                           },
 *                           "result": {
 *                               "id": 2,
 *                               "leave_request_id": 2,
 *                               "review_user_id": 1,
 *                               "is_allowed": 1,
 *                               "user": {
 *                                   "id": 1,
 *                                   "name": "admin1",
 *                                   "email": "admin1@gmail.com",
 *                                   "is_admin": 1
 *                               }
 *                           }
 *                       }
 *                    },
 *                    "first_page_url": "http://localhost:8000/api/leave?page=1",
 *                    "from": 1,
 *                    "last_page": 1,
 *                    "last_page_url": "http://localhost:8000/api/leave?page=1",
 *                    "links": {
 *                       {
 *                           "url": null,
 *                           "label": "&laquo; Previous",
 *                           "active": false
 *                       },
 *                       {
 *                           "url": "http://localhost:8000/api/leave?page=1",
 *                           "label": "1",
 *                           "active": true
 *                       },
 *                       {
 *                           "url": null,
 *                           "label": "Next &raquo;",
 *                           "active": false
 *                       }
 *                    },
 *                    "next_page_url": null,
 *                    "path": "http://localhost:8000/api/leave",
 *                    "per_page": 10,
 *                    "prev_page_url": null,
 *                    "to": 2,
 *                    "total": 2
 *                }
 *            ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 * )

 
 */
abstract class Controller
{
    //
}