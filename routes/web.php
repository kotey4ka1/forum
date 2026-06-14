<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Admin\AdminAdController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminSectionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\KnowledgeBaseAdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\PasswordChangeConfirmController;

Auth::routes(['verify' => true]);

// Публичные маршруты
Route::get('/', [ForumController::class, 'index'])->name('home');
Route::get('/home', [ForumController::class, 'index'])->name('home');
Route::get('/section/{id}', [ForumController::class, 'section'])->name('forum.section');
Route::get('/post/{id}', [PostController::class, 'show'])->name('forum.post');
Route::get('/ad/click/{materialId}', [AdController::class, 'click'])->name('ad.click');
Route::post('/ad/impression/{materialId}', [AdController::class, 'impression'])->name('ad.impression');
Route::get('/faq', [KnowledgeBaseController::class, 'index'])->name('faq.index');
Route::get('/faq/{knowledgeBase}', [KnowledgeBaseController::class, 'show'])->name('faq.show');
Route::get('/search', [SearchController::class, 'index'])->name('search.results');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/tag/{id}', [App\Http\Controllers\TagController::class, 'show'])->name('forum.tag');

// Авторизованные пользователи (все)
Route::middleware(['auth'])->group(function () {
    Route::get('/section/{section}/post/create', [PostController::class, 'create'])->name('forum.post.create');
    Route::post('/section/{section}/post', [PostController::class, 'store'])->name('forum.post.store');
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/comment/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit');
    Route::put('/comment/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::post('/like', [LikeController::class, 'toggle'])->name('like.toggle');
    Route::get('/user/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('support', SupportRequestController::class)->except(['edit', 'update']);
    Route::post('/complaint', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('forum.post.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('forum.post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('forum.post.destroy');
    Route::post('/post/{id}/favorite', [PostController::class, 'toggleFavorite'])->name('post.favorite');
    Route::get('/favorites', [PostController::class, 'favorites'])->name('favorites.index');
    Route::get('/password/change/confirm/{token}', [PasswordChangeConfirmController::class, 'confirm'])
    ->name('password.change.confirm');
    Route::post('/profile/send-reset-link', [ProfileController::class, 'sendResetLink'])->name('profile.sendResetLink');

});

// Единая админ-панель для администраторов и модераторов
Route::middleware(['auth', 'admin_or_moderator'])->prefix('admin')->name('admin.')->group(function () {
    // Основные разделы (доступны всем, но в представлениях скрыты для модератора)
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::resource('sections', AdminSectionController::class);
    Route::get('posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::delete('posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('posts/{post}/pin', [AdminPostController::class, 'togglePin'])->name('posts.pin');
    Route::get('posts/{post}/move', [AdminPostController::class, 'moveForm'])->name('posts.move.form');
    Route::patch('posts/{post}/move', [AdminPostController::class, 'move'])->name('posts.move');
    Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    Route::resource('ads', AdminAdController::class)->except(['show']);
    Route::get('ads/{ad}/stats', [AdminAdController::class, 'stats'])->name('ads.stats');
    // Модерация (жалобы, обращения)
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
    Route::patch('/complaint/{complaint}/resolve', [ModerationController::class, 'resolveComplaint'])->name('complaint.resolve');
    Route::patch('/complaint/{complaint}/reject', [ModerationController::class, 'rejectComplaint'])->name('complaint.reject');
    Route::patch('/complaint/{complaint}/restore', [ModerationController::class, 'restoreComplaint'])->name('complaint.restore');
    Route::patch('/support/{supportRequest}/respond', [ModerationController::class, 'respondSupport'])->name('support.respond');

    // Управление FAQ (CRUD)
    Route::resource('faq', KnowledgeBaseAdminController::class)->except(['show']);
    // Восстановление контента (только для администраторов – проверка в контроллере)
    Route::patch('/post/{id}/restore', [PostController::class, 'restore'])->name('post.restore');
    Route::patch('/comment/{id}/restore', [CommentController::class, 'restore'])->name('comment.restore');
});
