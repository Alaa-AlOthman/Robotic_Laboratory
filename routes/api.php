<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailablePiecesController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\BorrowRequestPiecesController;
use App\Http\Controllers\BrokenPiecesController;
use App\Http\Controllers\CompoundController;
use App\Http\Controllers\CompoundPiecesController;
use App\Http\Controllers\LostPiecesController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::middleware('web')->group(function () {
//     Route::get('/setup', [UserController::class, 'CreateAdmin']);
//     Route::get('/getUsers', [UserController::class, 'getUsers']);
//     Route::post('/login', [UserController::class, 'login']);
//     Route::post('/logout', [UserController::class, 'logout']);
// });
//    Route::get('/setup', [UserController::class, 'CreateAdmin']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(JwtMiddleware::class)->group(function () {

    
    Route::controller(AuthController::class)->group(function () {
        Route::get('/user', 'getUser');
        Route::get('/logout', 'logout');
    });
    Route::controller(UserController::class)->group(function () {
        Route::post('/changePassword', 'changePassword');
        Route::get('/getUsers', 'getUsers');
        Route::post('/createUser', 'createUser');
        Route::post('/editUser', 'editUser');
        Route::post('/deleteUser', 'deleteUser');
    });

    Route::controller(StudentController::class)->group(function () {
        Route::post('/student/borrowed', 'borrowed');
        Route::get('/pieces', 'pieces');
    });

    Route::controller(AvailablePiecesController::class)->group(function () {
        Route::get('/getAllAvailablePieces', 'getAllAvailablePieces');
        Route::get('/getAllAvailablePiecesWithPaginate', 'getAllAvailablePiecesWithPaginate');
        Route::get('/getAllAvailablePiecestoExport', 'getAllAvailablePiecestoExport');
        Route::post('/addAvailablePiece', 'addAvailablePiece');
        Route::post('/EditeAvailablePiece', 'EditeAvailablePiece');
        Route::post('/deleteAvailablePiece', 'deleteAvailablePiece');
        Route::post('/searchAvailablePieces', 'searchAvailablePieces');
        Route::post('/getPieceDetails', 'getPieceDetails');
    });

    Route::controller(TeamController::class)->group(function () {
        Route::get('/getAllTeam', 'getAllTeam');
        Route::post('/getTeam', 'getTeam');
        Route::get('/getAllTeamWithPaginate', 'getAllTeamWithPaginate');
        Route::post('/addTeam', 'addTeam');
        Route::post('/editTeam', 'editTeam');
        Route::post('/deleteTeam', 'deleteTeam');
    });

    Route::controller(BorrowRequestController::class)->group(function () {
        Route::post('/createNewBorrowRequest', 'createNewBorrowRequest');
        Route::post('/AddToBorrowRequest', 'AddToBorrowRequest');
        Route::post('/getAllBorrowRequests', 'getAllBorrowRequests');
        Route::get('/getAllBorrowRequestsWithoutPaginate', 'getAllBorrowRequestsWithoutPaginate');
        Route::get('/getRecordsForSelect', 'getRecordsForSelect');
        Route::post('/updateBorrowRequest', 'updateBorrowRequest');
        Route::post('/deleteBorrowRequest', 'deleteBorrowRequest');
    });

    Route::controller(BorrowRequestPiecesController::class)->group(function () {
        Route::post('/addPieceToBorrowRequest', 'addPieceToBorrowRequest');
        Route::post('/editePieceInBorrowRequest', 'editePieceInBorrowRequest');
        Route::post('/getPiecesByBorrowRequest', 'getPiecesByBorrowRequest');
        Route::post('/deletePieceFromBorrowRequest', 'deletePieceFromBorrowRequest');
        Route::post('/deleteAllPiecesFromBorrowRequets', 'deleteAllPiecesFromBorrowRequets');
        Route::post('/getBorrowRequestByDiawanNum', 'getBorrowRequestByDiawanNum');
        Route::post('/recievePieceFromStudent', 'recievePieceFromStudent');
        Route::post('/getPiecesByBorrowRequestWithoutPigination', 'getPiecesByBorrowRequestWithoutPigination');
        Route::post('/recieveBorrowRequest', 'recieveBorrowRequest');
        Route::post('/importPiecesFromExcel', 'importPiecesFromExcel');

        
    });

    Route::controller(LostPiecesController::class)->group(function () {
        Route::get('/getAllLostPieces', 'getAllLostPieces');
        Route::post('/createLostPiece', 'createLostPiece');
        Route::post('/addToLostPiece', 'addToLostPiece');
        Route::post('/editLostPiece', 'editLostPiece');
        Route::post('/providePiece', 'providePiece');
        Route::post('/searchLostPieces', 'searchLostPieces');
        Route::post('/removeLostPiece', 'removeLostPiece');
    });

    Route::controller(BrokenPiecesController::class)->group(function () {
        Route::get('/getAllBrokenPieces', 'getAllBrokenPieces');
        Route::post('/createBrokenPiece', 'createBrokenPiece');
        Route::post('/addToBrokenPieces', 'addToBrokenPieces');
        Route::post('/removeBrokenPiece', 'removeBrokenPiece');
        Route::post('/editBrokenPiece', 'editBrokenPiece');
        Route::post('/repairBrokenPiece', 'repairBrokenPiece');
        Route::post('/searchBrokenPieces', 'searchBrokenPieces');
    });

    Route::controller(CompoundController::class)->group(function () {
        Route::get('/getAllCompounds', 'getAllCompounds');
        Route::post('/createNewCompound', 'createNewCompound');
        Route::post('/editeCompound', 'editeCompound');
        Route::post('/deleteComound', 'deleteComound');
    });

    Route::controller(CompoundPiecesController::class)->group(function () {
        Route::post('/getPiecesCompound', 'getPiecesCompound');
        Route::post('/addPieceToCompound', 'addPieceToCompound');
        Route::post('/editePieceInCompound', 'editePieceInCompound');
        Route::post('/deletePieceFromCompound', 'deletePieceFromCompound');
    });
});
