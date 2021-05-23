<?php

// Route::resource('articles', 'Admin\ApiController');
// Route::post('getinfo', 'Api\V2\BaseController@getInfo');
//

Route::group(['prefix' => 'v2', 'as' => 'api.', 'namespace' => 'Api\V2', 'middleware' => ['auth']], function () {

    // Regions
    Route::apiResource('regions', 'RegionController');

    // Branches
    Route::apiResource('branches', 'BranchController');

    // Products
    Route::apiResource('products', 'ProductController');

    // DopProducts
    Route::apiResource('dopproducts', 'DopProductController');

    // DopProducts
    Route::apiResource('halls', 'HallController');

    // Schedule_hall
    Route::apiResource('schedule_hall', 'Schedule_hallController');

    // Programms
    Route::apiResource('programms', 'ProgrammController');
    Route::post('updateTypeProgramm', 'ProgrammController@updateTypeProgramm');

    // Groups
    Route::apiResource('journal_groups', 'GroupController');

    // Product_pay
    Route::apiResource('product_pay', 'Product_payController');

    // Permissions
    Route::apiResource('apipermissions', 'PermissionsController');

    // Documents
    Route::apiResource('documents', 'DocumentController');

    // OperationTypes
    Route::apiResource('operation_type', 'OperationTypeController');

    //--------------------- KassaOperation -------------------------//
    Route::apiResource('kassaOperations', 'KassaOperationController');
    Route::get('kassa_operation', 'KassaOperationController@index');
    Route::post('kassaSettingsEditOperationName', 'KassaOperationController@editNameInKassaSettings');
    Route::post('delete_operation', 'KassaOperationController@destroy');
    Route::post('kassaSettingsEditOperationKassa', 'KassaOperationController@editKassaInKassaSettings');
    Route::post('kassaSettingsEditOperationCheckbox', 'KassaOperationController@editCheckboxInKassaSettings');
    Route::post('kassaSettingsGetOperationData', 'KassaOperationController@getCheckboxDataInKassaSettings');
    Route::post('addOperation', 'KassaOperationController@addOperation');
    Route::post('getOperationsType', 'KassaOperationController@getOperationsType');
    Route::post('getRadioButton', 'KassaOperationController@getRadioButton');
    Route::post('showKassaOperation', 'KassaOperationController@showKassaOperation');

    //---------------------- KassaGroup ---------------------------//

    Route::apiResource('kassaGroups', 'KassaGroupController');
    Route::post('kassas', 'KassaGroupController@getGroupInKassa');
    Route::post('kassaSettingsEditGroupsName', 'KassaGroupController@editNameInKassaSettings');
    Route::post('kassaSettingsEditGroupsKassa', 'KassaGroupController@editKassaInKassaSettings');

    // SourceGraoup
    Route::apiResource('sourceGroups', 'SourceGroupController');
    Route::post('groups', 'SourceGroupController@getGroupInSource');

    // Source
    Route::apiResource('source', 'SourceController');

    // Contracts
    Route::post('getcontract', 'ContractController@getContract');
    Route::post('getcontracts', 'ContractController@getContracts');
    Route::post('showcontract', 'ContractController@showContract');
    Route::post('savecontract', 'ContractController@saveContractAndEditBaseFields');
    Route::post('editContract', 'ContractController@editContract');

    // Journals
    Route::post('workout', 'BaseController@workout');
    Route::post('notVisit', 'BaseController@notVisit');
    Route::post('newWorkout', 'BaseController@newWorkout');
    Route::post('freezing', 'BaseController@freezing');
    Route::post('addNewComent', 'BaseController@addNewComent');
    Route::post('showHistory', 'BaseController@showHistory');
    Route::post('showhall', 'BaseController@showHall');
    Route::post('schedule', 'BaseController@Schedule');
    Route::post('deleteSchedule', 'BaseController@deleteSchedule');
    Route::post('saveSchedule', 'BaseController@saveSchedule');
    Route::post('getGroupInHall', 'BaseController@getGroupInHall');
    Route::post('getEditingGroup', 'BaseController@getEditingGroup');
    Route::post('getPkProgramm', 'BaseController@getPkProgramm');

    // Base
    Route::post('getinfo', 'BaseController@getInfo');
    Route::get('collection', 'BaseController@index');
    Route::get('getAgregatorLids', 'BaseController@getAgregatorLids');
    Route::post('image', 'BaseController@upload');
    Route::post('updateLpr', 'BaseController@updateLpr');
    Route::post('removeblock', 'BaseController@removeBlock');
    Route::post('changeCallDate', 'StatusesController@changeCallDate');
    Route::post('changeCallStatus', 'StatusesController@changeCallStatus');
    Route::post('addnewuser', 'BaseController@addNewUser');
    Route::post('baseFilter', 'BaseController@baseFilter');
    Route::post('getSourceInGroup', 'SourceController@getSourceInGroup');
    Route::get('getSourceGroupBaseModal', 'SourceGroupController@index');
    Route::post('saveNewPay', 'BaseController@saveNewPay');
    Route::post('saveNewBalance', 'BaseController@saveNewBalance');
    Route::post('getLog', 'LogerController@index');
    Route::post('freezingOff', 'BaseController@freezingOff');
    Route::post('getBaseKit', 'BaseController@getBaseKit');

    // Roles
    Route::apiResource('roles', 'RoleController');
    Route::get('getAllPermissions', 'RoleController@getAllPermissions');
    Route::post('saveRole', 'RoleController@saveRole');
    Route::post('saveRoleField', 'RoleController@saveRoleField');

    // UserAccess
    Route::get('userAccess', 'UserAccessController@index');
    Route::post('showUserAccess', 'UserAccessController@showUserAccess');
    Route::post('saveUserAccess', 'UserAccessController@saveUserAccess');

    // Warehouse
    Route::apiResource('warehouses', 'WarehouseController');
    Route::post('getBranchesWarehouse', 'WarehouseController@getBranches');
    Route::get('getRegionsWarehouse', 'WarehouseController@getRegions');
    Route::post('getUsersWarehouse', 'WarehouseController@getUsers');
    Route::post('warehouseLog', 'WarehouseController@warehouseLog');

    // Article
    Route::apiResource('article', 'ArticleController');
    Route::post('getArticleInGroup', 'ArticleController@getArticleInGroup');

    //WarehouseArticle
    Route::apiResource('warehouse_article', 'WarehouseArticleController');
    Route::post('showWarehouseArticle', 'WarehouseArticleController@showWarehouseArticle');
    Route::post('addQuantity', 'WarehouseArticleController@addQuantity');
    Route::post('moveArticle', 'WarehouseArticleController@moveArticle');
    Route::post('removeQuantity', 'WarehouseArticleController@removeQuantity');
    Route::post('removeBase', 'WarehouseArticleController@removeBase');
    Route::post('removeUser', 'WarehouseArticleController@removeUser');
    Route::post('usersInBranch', 'WarehouseArticleController@usersInBranch');
    Route::post('basesInBranch', 'WarehouseArticleController@basesInBranch');

    // ArticleGroups
    Route::apiResource('article_groups', 'ArticleGroupController');

    // Suppliers
    Route::apiResource('suppliers', 'SupplierController');

    // Users
    Route::apiResource('users', 'UserController');
    Route::get('getatributes', 'UserController@getAtributes');
    Route::post('history', 'UserController@history');
    Route::get('getUserName', 'UserController@getUserNameFromMenu');
    Route::get('getAllBranches', 'UserController@getAllBranches');
    Route::get('getAllRoles', 'UserController@getAllRoles');
    Route::post('saveBranches', 'UserController@saveBranches');
    Route::post('saveRoles', 'UserController@saveRoles');
    Route::get('getGates', 'UserController@getGates');
    Route::post('getUserKit', 'UserController@getUserKit');
    Route::post('getFilter', 'UserController@getFilter');


    Route::post('addClientInGroup', 'BaseController@addClientInGroup');
    Route::post('saveClientInGroup', 'BaseController@saveClientInGroup');
    Route::post('updatetest', 'BaseController@updateTest');


    Route::post('getuseringroup', 'BaseController@getUserInGroup');
    Route::post('showprogramms', 'BaseController@showHProgramms');
    Route::post('uploadDocument', 'BaseController@uploadDocument');
    Route::post('gethalls', 'HallController@getHalls');
    Route::post('getProducts', 'HallController@getProducts');
    Route::post('getgroup', 'GroupController@getgroup');
    Route::post('addClientFromPromoter', 'BaseController@addClientFromPromoter');


    Route::get('getbranches', 'BaseController@getBranches');
    Route::get('getmanagers', 'BaseController@getManagers');
    Route::get('getinstructors', 'BaseController@getInstructors');
    Route::post('getprogramms', 'BaseController@getProgramms');
    Route::get('getusers', 'BaseController@getUsers');
    Route::post('getvmcontract', 'BaseController@getVmContract');
    Route::get('test', 'BaseController@test');

    Route::post('getTest', 'ContractController@test');
});



Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Lessons
    Route::apiResource('lessons', 'LessonsApiController');

    // School Classes
    Route::apiResource('school-classes', 'SchoolClassesApiController');
});
