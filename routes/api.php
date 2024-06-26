<?php

use App\Http\Controllers\Firebase\FirestoreController;
use App\Http\Controllers\Firebase\MessagingController;
use App\Http\Controllers\Firebase\StorageController;
use App\Http\Controllers\Messenger\MailController;
use App\Http\Controllers\Mobile\Auth\JwtMobileController;
use App\Http\Controllers\Mobile\Auth\MobileAuthController;
use App\Http\Controllers\Mobile\Auth\VerifyController;
use App\Http\Controllers\Mobile\Category\CategoryController;
use App\Http\Controllers\Mobile\Page\CustomerPageController;
use App\Http\Controllers\Mobile\Page\OrderMerchantController;
use App\Http\Controllers\Mobile\Page\ProductMerchantController;
use App\Http\Controllers\Mobile\Page\PromoMerchantController;
use App\Http\Controllers\Mobile\Product\ProductController;
use App\Http\Controllers\Mobile\Product\ProductComplainController;
use App\Http\Controllers\Mobile\Product\ProductHistoryController;
use App\Http\Controllers\Mobile\Product\ProductPromoController;
use App\Http\Controllers\Mobile\Product\ProductReviewController;
use App\Http\Controllers\Mobile\Product\UserProductController;
use App\Http\Controllers\Mobile\Transaction\TransactionController;
use App\Http\Controllers\Mobile\Transaction\UserTransactionController;
use App\Http\Controllers\Website\ShopController;
use App\Models\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => '/m'], function () {
    Route::group(['prefix' => '/test'], function () {
        Route::get('/first', [MobileAuthController::class, 'first']);
        Route::get('jwttrx', [JwtMobileController::class, 'generateTokenTrx']);
    });

    // user route
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/cekemail', [MobileAuthController::class, 'isExistEmail']);
        Route::get('/cekphone', [MobileAuthController::class, 'isExistPhone']);
        Route::get('/islogin', [MobileAuthController::class, 'isOnLogin']);
        Route::post('/signup', [MobileAuthController::class, 'register']);
        Route::group(['prefix' => 'signin'], function () {
            Route::post('/email', [MobileAuthController::class, 'signinEmail']);
            Route::post('/phone', [MobileAuthController::class, 'signinPhone']);
            Route::post('/google', [MobileAuthController::class, 'signinGoogle']);
        });
        Route::group(['prefix' => 'update'], function () {
            Route::post('/pp', [MobileAuthController::class, 'updatePhotoProfile']);
            Route::put('/pw', [MobileAuthController::class, 'changePassword']);
            Route::put('/pin', [MobileAuthController::class, 'changePin']);
            Route::put('/acc', [MobileAuthController::class, 'updateAccount']);
            Route::put('/devicetoken', [MobileAuthController::class, 'updateDeviceToken']);
        });
        Route::delete('/logout', [MobileAuthController::class, 'logout']);
        Route::delete('/delete', [MobileAuthController::class, 'deleteAccount']);
        Route::delete('/delpp', [MobileAuthController::class, 'deletePhotoProfile']);
    });

    // verify
    Route::group(['prefix' => 'verify'], function () {
        Route::post('/otp', [VerifyController::class, 'verify']);
        Route::post('/otpbyphone', [VerifyController::class, 'verifyByPhone']);
    });

    // messenger
    Route::group(['prefix' => 'messenger'], function () {
        Route::get('/test', [MailController::class, 'sendEmail']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'allCategories']);
        Route::get('/prod', [CategoryController::class, 'allCategoryByProduct']);
    });

    // product
    Route::group(['prefix' => 'prod'], function () {
        Route::get('/', [ProductController::class, 'allProducts']);
        Route::get('/fire', [ProductController::class, 'toFire']);
        Route::get('/t', [ProductController::class, 'testAll']);
        Route::post('/create', [ProductController::class, 'createProduct']);
        Route::get('/units', [ProductController::class, 'getUnits']);
        Route::get('/data', [ProductController::class, 'dataProduct']);
        Route::get('/details', [ProductController::class, 'detailProduct']);
        Route::get('/hiddens', [ProductController::class, 'hiddenProducts']);
        Route::get('/recommendeds', [ProductController::class, 'recommendedProducts']);
        Route::get('/unavls', [ProductController::class, 'unavlProducts']);
        Route::get('/bestselling', [ProductController::class, 'bestSelling']);
        Route::group(['prefix' => 'update'], function () {
            Route::post('/data', [ProductController::class, 'updateProduct']);
            Route::post('/photo', [ProductController::class, 'updateProductPhoto']);
            Route::put('/stok', [ProductController::class, 'setStock']);
            Route::put('/visibility', [ProductController::class, 'setVisibility']);
            Route::put('/recommended', [ProductController::class, 'setRecommended']);
        });
        Route::delete('/delete', [ProductController::class, 'deleteProduct']);

        // review
        Route::group(['prefix' => '/rvw'], function () {
            Route::get('/', [ProductReviewController::class, 'getAllReview']);
            Route::get('/rwvprod', [ProductReviewController::class, 'getOnlyReviews']);
            Route::get('/prod', [ProductReviewController::class, 'getReviews']);
            Route::post('/add', [ProductReviewController::class, 'addReview']);
            Route::put('/update', [ProductReviewController::class, 'updateReview']);
            Route::delete('/delete', [ProductReviewController::class, 'deleteReview']);
            Route::get('/highest', [ProductReviewController::class, 'getHighestReview']);
            Route::get('/is', [ProductReviewController::class, 'isReview']);
        });

        // complain
        Route::group(['prefix' => '/comp'], function () {
            // Route::get('/', [ProductComplainController::class, 'getAllComplains']);
            Route::get('/', [ProductComplainController::class, 'getComplains']);
            Route::get('/is', [ProductComplainController::class, 'isComplain']);
            Route::post('/add', [ProductComplainController::class, 'addComplain']);
            Route::put('/update', [ProductComplainController::class, 'updateComplain']);
            Route::delete('/delete', [ProductComplainController::class, 'deleteComplain']);
        });

        // history
        Route::group(['prefix' => '/hist'], function () {
            Route::get('/prod', [ProductHistoryController::class, 'historyProduct']);
        });

        // promo
        Route::group(['prefix' => '/promo'], function () {
            Route::get('/', [ProductPromoController::class, 'getPromos']);
            Route::get('/ispromo', [ProductPromoController::class, 'isPromo']);
            Route::get('/detail', [ProductPromoController::class, 'detailPromo']);
            Route::post('/create', [ProductPromoController::class, 'addPromo']);
            Route::put('/update', [ProductPromoController::class, 'updatePromo']);
            Route::get('/data', [ProductPromoController::class, 'getPromo']);
            Route::delete('/delete', [ProductPromoController::class, 'deletePromo']);
        });
    });

    Route::group(['prefix' => 'trx'], function () {
        Route::get('/', [TransactionController::class, 'listOfTrx']);
        Route::get('/e', [TransactionController::class, 'trxDetail']);
        Route::get('/sctrx', [TransactionController::class, 'scanTrx']);
        Route::post('/crtrx', [TransactionController::class, 'createTrx']);
        Route::put('/cbcus', [TransactionController::class, 'cancelByCustomer']);
        Route::put('/cbmer', [TransactionController::class, 'cancelByMerchant']);
        Route::put('/cftrx', [TransactionController::class, 'confirmTrx']);
        Route::put('/ittrx', [TransactionController::class, 'inTakingTrx']);
        Route::put('/sbtTrx', [TransactionController::class, 'submittedTrx']);
        Route::put('/fstrx', [TransactionController::class, 'finishTrx']);
        Route::post('/frtrx', [TransactionController::class, 'testTrx']);
    });

    Route::group(['prefix' => '/utrx'], function () {
        Route::get('/list', [UserTransactionController::class, 'listOfTrx']);
        Route::get('/data', [UserTransactionController::class, 'dataTrx']);
        Route::group(['prefix' => '/cart'], function () {
            Route::get('/', [UserTransactionController::class, 'getAllCart']);
            Route::post('/add', [UserTransactionController::class, 'addToCart']);
            Route::delete('/delete', [UserTransactionController::class, 'removeCart']);
            Route::put('/updateqt', [UserTransactionController::class, 'updateProductQuantity']);
        });
    });

    Route::group(['prefix' => '/uprod'], function () {
        Route::get('/ctg', [UserProductController::class, 'getAllCategories']);
        Route::get('/list', [UserProductController::class, 'getAllProducts']);
        Route::get('/detail', [UserProductController::class, 'detailProduct']);
    });

    Route::group(['prefix' => '/page'], function () {
        // merchant
        Route::group(['prefix' => 'merchant'], function () {
            Route::group(['prefix' => 'home'], function () {
                //
            });
            Route::group(['prefix' => 'prod'], function () {
                Route::get('/', [ProductMerchantController::class, 'page']);
                Route::get('/rvw', [ProductMerchantController::class, 'reviewPage']);
                Route::get('/comp', [ProductMerchantController::class, 'complainPage']);
                Route::get('/unavl', [ProductMerchantController::class, 'unvailablePage']);
                Route::get('/recommended', [ProductMerchantController::class, 'recommendedPage']);
                Route::get('/hidden', [ProductMerchantController::class, 'hiddenPage']);
                Route::get('/highest', [ProductMerchantController::class, 'highestPage']);
                Route::get('/selling', [ProductMerchantController::class, 'bestSellingPage']);
                Route::get('/detail', [ProductMerchantController::class, 'detailProduct']);
                Route::get('/drvw', [ProductMerchantController::class, 'detailListReview']);
                Route::get('/dcomp', [ProductMerchantController::class, 'detailListComplain']);
                Route::get('/dhist', [ProductMerchantController::class, 'detailListHistory']);
                Route::get('/category', [ProductMerchantController::class, 'listOfCategory']);
            });
            Route::group(['prefix' => 'promo'], function () {
                Route::get('/', [PromoMerchantController::class, 'listOfPromo']);
            });
            Route::group(['prefix' => 'trx'], function () {
                Route::get('/', [OrderMerchantController::class, 'listOfTrx']);
            });
        });

        // customer
        Route::group(['prefix' => 'customer'], function () {
            Route::get('/home', [CustomerPageController::class, 'beranda']);
            Route::get('/shop', [CustomerPageController::class, 'shopDetail']);
            Route::get('/promo', [CustomerPageController::class, 'promo']);
        });
    });
});

Route::group(['prefix' => 'shop'], function () {
    Route::get('/list', [ShopController::class, 'listOfShop']);
    Route::post('/create', [ShopController::class, 'createShop']);
    Route::put('/update', [ShopController::class, 'updateShop']);
    Route::put('/operational', [ShopController::class, 'updateOperational']);
    Route::delete('/delete', [ShopController::class, 'deleteShop']);
    Route::get('/contact', [ShopController::class, 'getContact']);
    Route::get('/data', [ShopController::class, 'getShopData']);
});


Route::group(['prefix' => '/fire'], function () {

    // firebase firestore
    Route::group(['prefix' => '/db'], function () {
        Route::get('/existcc', [FirestoreController::class, 'isExistCollection']);
        Route::post('/createcc', [FirestoreController::class, 'createCollection']);
        Route::delete('/deletecc', [FirestoreController::class, 'deleteCollection']);
        Route::post('/add', [FirestoreController::class, 'addUser']);
        Route::post('update', [FirestoreController::class, 'updateUser']);
        Route::delete('/delete', [FirestoreController::class, 'deleteUser']);
        Route::get('/students', [FirestoreController::class, 'getAllStudent']);
        Route::get('/existnim', [FirestoreController::class, 'isExistNim']);
        Route::get('/getid', [FirestoreController::class, 'getDocumentIdByNim']);
    });

    // firebase storage
    Route::group(['prefix' => '/storage'], function () {
        Route::post('/upload', [StorageController::class, 'uploadImage']);
        Route::get('/get', [StorageController::class, 'getImagePath']);
        Route::get('/exist', [StorageController::class, 'isExist']);
        Route::post('/update', [StorageController::class, 'updateImage']);
        Route::delete('/delete', [StorageController::class, 'deleteImage']);
    });

    // firebase messaging
    Route::group(['prefix' => '/messaging'], function () {
        // Route::post('/send', [MessagingController::class, 'sendNotificationrToUser']);
        Route::post('/send2', [MessagingController::class, 'sendNotification']);
    });
});
