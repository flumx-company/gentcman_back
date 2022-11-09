<?php

use Gentcmen\Http\Controllers\API\AdminController;
use Gentcmen\Http\Controllers\API\AuthController;
use Gentcmen\Http\Controllers\API\BlogCategoryController;
use Gentcmen\Http\Controllers\API\BlogController;
use Gentcmen\Http\Controllers\API\BasketController;
use Gentcmen\Http\Controllers\API\DevelopmentIdeaController;
use Gentcmen\Http\Controllers\API\ImprovementIdeaController;
use Gentcmen\Http\Controllers\API\PartnerController;
use Gentcmen\Http\Controllers\API\PostOfferController;
use Gentcmen\Http\Controllers\API\ProductCategoryController;

use Gentcmen\Http\Controllers\API\DeveloperController;
use Gentcmen\Http\Controllers\API\SendIdeaController;
use Gentcmen\Http\Controllers\API\ProductCategoryOptionController;
use Gentcmen\Http\Controllers\API\ProductController;
use Gentcmen\Http\Controllers\API\ProductStatusController;
use Gentcmen\Http\Controllers\API\ReviewController;
use Gentcmen\Http\Controllers\API\FaqCategoryController;
use Gentcmen\Http\Controllers\API\FaqSubCategoryController;
use Gentcmen\Http\Controllers\API\FavoriteController;
use Gentcmen\Http\Controllers\API\NotificationController;
use Gentcmen\Http\Controllers\API\OrderController;
use Gentcmen\Http\Controllers\API\PaymentController;
use Gentcmen\Http\Controllers\API\ReportProblemController;
use Gentcmen\Http\Controllers\API\SocialiteController;
use Gentcmen\Http\Controllers\API\TemplateMessageController;
use Gentcmen\Http\Controllers\API\UserController;
use Gentcmen\Http\Controllers\API\CouponController;
use Gentcmen\Http\Controllers\API\FaqController;
use Gentcmen\Http\Controllers\API\ReferralProgramController;
use Gentcmen\Http\Controllers\API\ReferralProgramStepController;
use Gentcmen\Http\Controllers\API\BannerController;
use Gentcmen\Http\Controllers\API\DiscountController;
use Illuminate\Support\Facades\Route;

Route::namespace('API')->group(function () {
    Route::group(['prefix' => 'v1/client'], function() {
        Route::post('sign-in', [AuthController::class, 'signIn']);
        Route::post('sign-up', [AuthController::class, 'signUp']);
        Route::post('send-recover-code', [AuthController::class, 'sendRecoverCode']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::get('check-exist-email', [AuthController::class, 'checkExistEmail']);

        Route::group(['prefix' => 'auth'], function () {
            Route::get('google', [SocialiteController::class, 'redirectToGoogle']);
            Route::get('google/callback', [SocialiteController::class, 'handleGoogleCallback']);

            Route::get('facebook', [SocialiteController::class, 'redirectToFacebook']);
            Route::get('facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);
        });

        Route::group(['prefix' => 'product-categories'], function () {
            Route::get('/', [ProductCategoryController::class, 'index']);
            Route::get('/{productCategory}', [ProductCategoryController::class, 'getById']);
        });

        Route::prefix('product-category-options')->group(function () {
            Route::get('/{productCategoryOption}', [ProductCategoryOptionController::class, 'getById']);
        });

        Route::group(['prefix' => 'product-statuses'], function () {
            Route::get('/', [ProductStatusController::class, 'index']);
            Route::get('/{status}', [ProductStatusController::class, 'getById']);
        });

        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [ProductController::class, 'searchByQuery']);
	    Route::get('/admin', [ProductController::class, 'searchByQueryAdmin']);
            Route::get('/query/popular-products', [ProductController::class, 'fetchPopularProducts']);
            Route::get('/query/by-categories', [ProductController::class, 'fetchByCategories']);
            Route::get('/{product}', [ProductController::class, 'getById']);

            Route::group(['prefix' => '{product}'], function () {
                Route::get('/reviews', [ReviewController::class, 'index']);
                Route::post('/reviews', [ReviewController::class, 'store'])->middleware('auth:api');
                Route::post('/reviews/{review}', [ReviewController::class, 'update'])->middleware('auth:api');
                Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->middleware('auth:api');
            });
        });

        Route::group(['prefix' => 'faq-categories'], function () {
            Route::get('/', [FaqCategoryController::class, 'index']);
            Route::get('/{faqCategory}', [FaqCategoryController::class, 'getById']);
        });

        Route::group(['prefix' => 'faqs'], function () {
            Route::post('/faq-answer-not-found', [FaqController::class, 'answerNotFound']);
        });

        Route::group(['prefix' => 'ideas'], function () {
            Route::post('/', [SendIdeaController::class, 'sendIdea']);
        });

        Route::group(['prefix' => 'report-problem'], function () {
            Route::get('/', [ReportProblemController::class, 'index']);
            Route::post('/', [ReportProblemController::class, 'store']);
        });

        Route::group(['prefix' => 'faq-subcategories'], function () {
            Route::get('/', [FaqSubCategoryController::class, 'index']);
            Route::get('/{entity}', [FaqSubCategoryController::class, 'getById']);
        });

        Route::prefix('blog')->group(function () {
            Route::get('/', [BlogController::class, 'index']);
            Route::get('/{blog}', [BlogController::class, 'getById']);
        });

        Route::prefix('blog-categories')->group(function () {
            Route::get('/', [BlogCategoryController::class, 'index']);
            Route::get('/{blogCategory}', [BlogCategoryController::class, 'getById']);
        });

	    Route::prefix('developers')->group(function () {
            Route::get('/', [DeveloperController::class, 'index']);
            Route::get('/{developer}', [DeveloperController::class, 'getById']);
        });

        Route::post('/development-ideas', [DevelopmentIdeaController::class, 'store']);
	Route::prefix('post-offers')->group(function () {
           Route::post('/', [PostOfferController::class, 'store']);
        });

	Route::get('/confirm-email', [AuthController::class, 'confirmEmail']);

        Route::group(['middleware' => 'auth.api'], function () {
	    Route::post('/confirm-email', [AuthController::class, 'sendConfirmEmail']);
             Route::group(['prefix' => 'user'], function () {
                Route::get('/', [UserController::class, 'index']);
                Route::post('/', [UserController::class, 'uploadImage']);
                Route::put('/', [UserController::class, 'update']);
		Route::get('/product-reviews', [UserController::class, 'fetchUserProductsWithReviews']);

                Route::get('/faq-history', [FaqController::class, 'fetchFaqHistory']);
                Route::get('/coupons', [UserController::class, 'fetchUserCoupons']);
		        Route::get('/orders', [OrderController::class, 'indexUser']);

		        Route::group(['prefix' => 'views'], function () {
                     Route::get('/', [UserController::class, 'viewHistory']);
                     Route::post('/', [UserController::class, 'saveViewHistory']);
                     Route::delete('/{viewableId}', [UserController::class, 'removeViewHistoryItem']);
                    // Route::delete('/', [UserController::class, 'clearAll']);
		             Route::delete('/', [UserController::class, 'resetAllUserViews']);
		        });

                 Route::group(['prefix' => 'coupons'], function () {
                     Route::delete('/', [UserController::class, 'destroyUserCoupons']);
                     Route::post('/{coupon}', [UserController::class, 'buyCoupon']);
                 });

                Route::delete('/remove-avatar', [UserController::class, 'deleteAvatar']);
            });

            Route::delete('logout', [AuthController::class, 'logout']);

            Route::group(['prefix' => 'buckets'], function () {
                Route::get('/', [BasketController::class, 'index']);
                Route::get('/identifiers', [BasketController::class, 'basketIdentifiers']);
                Route::post('/', [BasketController::class, 'store']);
                Route::put('/{bucket}', [BasketController::class, 'update']);
                Route::delete('/', [BasketController::class, 'destroy']);
                Route::delete('/clear-all', [BasketController::class, 'clearAll']);
            });

            Route::group(['prefix' => 'favorites'], function () {
                Route::get('/', [FavoriteController::class, 'index']);
                Route::get('/identifiers', [FavoriteController::class, 'favoriteIdentifiers']);
                Route::post('/', [FavoriteController::class, 'store']);
                Route::delete('/', [FavoriteController::class, 'destroy']);
            });

	    Route::prefix('post-offers')->group(function () {
    	        Route::get('/', [PostOfferController::class, 'index']);
    	    });

    	    Route::prefix('idea-improvements')->group(function () {
        	Route::get('/', [ImprovementIdeaController::class, 'index']);
        	Route::post('/', [ImprovementIdeaController::class, 'store']);
        	Route::put('/{improvement}', [ImprovementIdeaController::class, 'update']);
    	    });

    	    Route::prefix('development-ideas')->group(function () {
        	Route::get('/', [DevelopmentIdeaController::class, 'index']);
        	Route::put('/{idea}', [DevelopmentIdeaController::class, 'update']);
    	    });

	    Route::group(['prefix' => 'faqs'], function () {
                Route::get('/', [FaqController::class, 'index']);
	    });
        });

        Route::post('/payment', [PaymentController::class, 'makePayment']);
        Route::post('/payment/result', [PaymentController::class, 'paymentSuccess']);

        Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::get('/{coupon}', [CouponController::class, 'getById']);
        });

        Route::prefix('referral-programs')->group(function () {
            Route::get('/', [ReferralProgramController::class, 'index']);
            Route::get('/{referralProgram}', [ReferralProgramController::class, 'getById']);
        });

        Route::prefix('developers')->group(function () {
            Route::get('/', [DeveloperController::class, 'index']);
            Route::get('/{developer}', [DeveloperController::class, 'getById']);
        });

        Route::prefix('banners')->group(function () {
            Route::get('/', [BannerController::class, 'index']);
            Route::get('/{banner}', [BannerController::class, 'getById']);
        });

        Route::prefix('partners')->group(function () {
            Route::get('/', [PartnerController::class, 'index']);
            Route::get('/{partner}', [PartnerController::class, 'getById']);
        });
    });

    Route::group(['prefix' => 'v1/admin', 'middleware' => ['auth.api', 'role:Admin']], function() {

        Route::post('/create-filter', [AdminController::class, 'createFilter']);
	    Route::get('/me', [AdminController::class, 'getAdmin']);
        Route::patch('/{admin}', [AdminController::class, 'patchUpdate']);

        Route::group(['prefix' => 'notifications'], function() {
            Route::get('/', [NotificationController::class, 'index']);
            Route::get('/{notification}', [NotificationController::class, 'markNotificationAsRead']);
            Route::get('/mark/markAllNotifications', [NotificationController::class, 'markAllNotifications']);
            Route::delete('/{notification}', [NotificationController::class, 'destroyNotification']);
        });

        Route::group(['prefix' => 'users'], function() {
            Route::get('/', [AdminController::class, 'querySearch']);
            Route::get('/{user}', [AdminController::class, 'getById']);
            Route::get('/{user}/coupons', [AdminController::class, 'getUserCoupons']);
            Route::post('/create-admin', [AdminController::class, 'createAdmin']);
            Route::delete('/{user}', [AdminController::class, 'destroy']);
            Route::post('/{user}/manage-bonus-points', [AdminController::class, 'manageBonusPoints']);
            Route::post('/{user}/manage-coupons', [AdminController::class, 'manageCoupons']);
            Route::delete('/{user}/manage-coupons', [AdminController::class, 'removeUserCoupon']);
        });

        Route::prefix('blog')->group(function () {
            Route::get('/', [BlogController::class, 'index']);
            Route::post('/', [BlogController::class, 'store']);
            Route::put('/{blog}', [BlogController::class, 'update']);
            Route::delete('/', [BlogController::class, 'destroySomeItems']);
            Route::delete('/{blog}', [BlogController::class, 'destroy']);
        });

        Route::prefix('blog-categories')->group(function () {
            Route::post('/', [BlogCategoryController::class, 'store']);
            Route::put('/{blogCategory}', [BlogCategoryController::class, 'update']);
            Route::delete('/', [BlogCategoryController::class, 'destroySomeItems']);
            Route::delete('/{blogCategory}', [BlogCategoryController::class, 'destroy']);
        });

        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{product}', [ProductController::class, 'update']);
            Route::delete('/', [ProductController::class, 'destroySomeItems']);
            Route::delete('/{product}', [ProductController::class, 'destroy']);

            Route::post('/{product}/discount', [ProductController::class, 'createDiscount']);
        });

        Route::group(['prefix' => 'report-problem'], function () {
            Route::get('/', [ReportProblemController::class, 'adminIndex']);
            Route::patch('/{problem}', [ReportProblemController::class, 'patchUpdate']);
            Route::delete('/{problem}', [ReportProblemController::class, 'destroy']);
            Route::delete('/', [ReportProblemController::class, 'destroySomeItems']);
        });

        Route::prefix('discounts')->group(function () {
            Route::put('/{discount}', [DiscountController::class, 'updateDiscount']);
            Route::delete('/{discount}', [DiscountController::class, 'removeDiscount']);
        });

        Route::prefix('product-categories')->group(function () {
            Route::post('/', [ProductCategoryController::class, 'store']);
            Route::put('/{category}', [ProductCategoryController::class, 'update']);
            Route::delete('/', [ProductCategoryController::class, 'destroySomeItems']);
            Route::delete('/{category}', [ProductCategoryController::class, 'destroy']);
            Route::post('/{categoryId}/options', [ProductCategoryOptionController::class, 'store']);

            Route::post('/{productCategory}/discount', [ProductCategoryController::class, 'createDiscount']);
        });

        Route::prefix('product-category-options')->group(function () {
            Route::put('/{option}', [ProductCategoryOptionController::class, 'update']);
            Route::delete('/{option}', [ProductCategoryOptionController::class, 'destroy']);
        });

        Route::prefix('product-statuses')->group(function () {
            Route::post('/', [ProductStatusController::class, 'store']);
            Route::put('/{status}', [ProductStatusController::class, 'update']);
            Route::delete('/', [ProductStatusController::class, 'destroySomeItems']);
            Route::delete('/{status}', [ProductStatusController::class, 'destroy']);
        });
        Route::group(['prefix' => 'faqs'], function () {
            Route::get('/', [FaqController::class, 'adminIndex']);
            Route::get('/{faq}', [FaqController::class, 'getById']);
            Route::patch('/{faq}', [FaqController::class, 'patchUpdate']);
            Route::delete('/', [FaqController::class, 'destroySomeItems']);
            Route::delete('/{faq}', [FaqController::class, 'destroy']);
        });

        Route::prefix('faq-categories')->group(function () {
            Route::post('/', [FaqCategoryController::class, 'store']);
            Route::post('/{category}/faq-subcategory', [FaqSubCategoryController::class, 'store']);
            Route::put('/{category}', [FaqCategoryController::class, 'update']);
            Route::delete('/', [FaqCategoryController::class, 'destroySomeItems']);
            Route::delete('/{category}', [FaqCategoryController::class, 'destroy']);
        });

        Route::prefix('faq-subcategories')->group(function () {
	    Route::post('/', [FaqSubCategoryController::class, 'store']);
            Route::put('/{entity}', [FaqSubCategoryController::class, 'update']);
            Route::delete('/', [FaqSubCategoryController::class, 'destroySomeItems']);
            Route::delete('/{entity}', [FaqSubCategoryController::class, 'destroy']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::patch('/{order}', [OrderController::class, 'patchUpdate']);
            Route::delete('/', [OrderController::class, 'destroy']);
        });

        Route::prefix('developers')->group(function () {
            Route::post('/', [DeveloperController::class, 'store']);
            Route::put('/{developer}', [DeveloperController::class, 'update']);
            Route::delete('/', [DeveloperController::class, 'destroySomeItems']);
            Route::delete('/{developer}', [DeveloperController::class, 'destroy']);
        });

        Route::prefix('coupons')->group(function () {
            Route::post('/', [CouponController::class, 'store']);
            Route::put('/{coupon}', [CouponController::class, 'update']);
            Route::delete('/{coupon}', [CouponController::class, 'destroy']);
            Route::delete('/', [CouponController::class, 'destroySomeItems']);
            Route::post('/restore/{id}', [CouponController::class, 'restore']);
            Route::delete('/soft-delete/permanent-delete', [CouponController::class, 'permanentDeleteSoftDeleted']);
        });

        Route::prefix('referral-programs')->group(function () {
            Route::post('/', [ReferralProgramController::class, 'store']);
            Route::put('/{program}', [ReferralProgramController::class, 'update']);
            Route::delete('/{program}', [ReferralProgramController::class, 'destroy']);
            Route::post('/{referralProgram}/referral-program-step', [ReferralProgramStepController::class, 'store']);
        });

        Route::prefix('referral-program-steps')->group(function () {
            Route::put('/{step}', [ReferralProgramStepController::class, 'update']);
            Route::delete('/', [ReferralProgramStepController::class, 'destroySomeItems']);
            Route::delete('/{step}', [ReferralProgramStepController::class, 'destroy']);
        });

        Route::prefix('banners')->group(function () {
            Route::post('/', [BannerController::class, 'store']);
            Route::put('/{banner}', [BannerController::class, 'update']);
            Route::delete('/', [BannerController::class, 'destroySomeItems']);
            Route::delete('/{banner}', [BannerController::class, 'destroy']);
        });

        Route::prefix('partners')->group(function () {
            Route::post('/', [PartnerController::class, 'store']);
            Route::put('/{partner}', [PartnerController::class, 'update']);
            Route::delete('/', [PartnerController::class, 'destroySomeItems']);
            Route::delete('/{partner}', [PartnerController::class, 'destroy']);
        });

        Route::prefix('post-offers')->group(function () {
	    Route::get('/', [PostOfferController::class, 'adminIndex']);
            Route::patch('/{offer}', [PostOfferController::class, 'patchUpdate']);
            Route::delete('/', [PostOfferController::class, 'destroySomeItems']);
            Route::delete('/{offer}', [PostOfferController::class, 'destroy']);
        });

        Route::prefix('template-messages')->group(function () {
            Route::get('/', [TemplateMessageController::class, 'index']);
            Route::post('/', [TemplateMessageController::class, 'store']);
            Route::put('/{template}', [TemplateMessageController::class, 'update']);
            Route::delete('/', [TemplateMessageController::class, 'destroySomeItems']);
            Route::delete('/{template}', [TemplateMessageController::class, 'destroy']);
        });

        Route::prefix('idea-improvements')->group(function () {
            Route::get('/', [ImprovementIdeaController::class, 'adminIndex']);
            Route::patch('/{improvement}', [ImprovementIdeaController::class, 'patchUpdate']);
            Route::delete('/', [ImprovementIdeaController::class, 'destroySomeItems']);
            Route::delete('/{improvement}', [ImprovementIdeaController::class, 'destroy']);
        });

        Route::prefix('development-ideas')->group(function () {
            Route::get('/', [DevelopmentIdeaController::class, 'adminIndex']);
            Route::patch('/{idea}', [DevelopmentIdeaController::class, 'patchUpdate']);
            Route::delete('/', [DevelopmentIdeaController::class, 'destroySomeItems']);
            Route::delete('/{idea}', [DevelopmentIdeaController::class, 'destroy']);
        });
    });
});
