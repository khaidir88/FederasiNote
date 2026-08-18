<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController,
    DashboardController,
    PermissionController,
    PasswordResetLinkController,
    NewPasswordController,
    UserController,
    CategoryController,
    ProfileController,
    RoleController,
    ArticleController,
    CommentController,
    DinasController,
    AgendaController,
    SearchController,
    KementerianController,
    NewsController,

    Auth\RegisteredUserController
};

// =====================
// PUBLIC ROUTES
// =====================
Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/news', [PageController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [PageController::class, 'show'])->name('news.show');
Route::get('/live-search', [PageController::class, 'liveSearch'])->name('news.liveSearch');


    Route::resource('berita', NewsController::class, [
        'parameters' => ['berita' => 'news']
    ])->except(['show']);
// Route::get('/search', [PageController::class, 'search'])->name('news.search');

// Routes Dinas
Route::get('/dinas', [KementerianController::class, 'index'])->name('kementerian.index');
Route::get('/dinas/kementerians', [KementerianController::class, 'kementerians'])->name('kementerian.kementerian');
Route::get('/dinas/kota', [KementerianController::class, 'kota'])->name('kementerian.kota');
Route::get('/dinas/provinsi', [KementerianController::class, 'provinsi'])->name('kementerian.provinsi');
Route::get('/dinas/{slug}', [KementerianController::class, 'showdinas'])->name('kementerian.dinas');
//new code
Route::get('/dinas/search', [KementerianController::class, 'search'])->name('dinas.search');


// Routes Agenda
Route::get('/agenda', [KementerianController::class, 'allAgendas'])->name('kementerian.agendas');

Route::get('/agenda/{slug}', [KementerianController::class, 'details'])->name('kementerian.agenda-details');
Route::get('/dinas/{dinasSlug}/agenda/{agendaSlug}', [KementerianController::class, 'agendaByDinas'])->name('kementerian.agenda-by-dinas');
Route::get('/agenda-search', [KementerianController::class, 'searchAgenda'])->name('kementerian.search-agenda');
Route::get('/api/agendas/{dinasId}', [KementerianController::class, 'getAgendasByDinas'])->name('kementerian.api.agendas');
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/news/{parent}/{child?}', [CategoryController::class, 'show'])
    ->name('category.show');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');


// Unique check route (gunakan salah satu controller saja)
Route::post('/check-unique', [RegisteredUserController::class, 'checkUnique'])->name('check.unique');


///tambhakan middleware
// Auth & User role routes
Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    // List komentar
    Route::get('/comments', [CommentController::class, 'index'])
        ->name('comments.index');

    // Approve komentar
    Route::patch('/comments/{id}/approve', [CommentController::class, 'approve'])
        ->name('comments.approve');
    Route::patch(
        '/comments/{id}/unapprove',
        [CommentController::class, 'unapproveComment']
    )->name('comments.unapprove');
    Route::patch('/comments/{id}/reject', [CommentController::class, 'reject'])
        ->name('comments.reject');

    // Hapus komentar
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::resource('dinas', DinasController::class);
    Route::resource('categories', CategoryController::class)
        ->except(['show']);

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('agendas', AgendaController::class);
    Route::resource('articles', ArticleController::class);


    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::patch('/articles/{news}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
    Route::patch('/articles/{news}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');
    Route::patch('/articles/{news}/archive', [ArticleController::class, 'archive'])->name('articles.archive');
    Route::delete('/articles/{id}/force-delete', [ArticleController::class, 'forceDelete'])->name('articles.force-delete');
    Route::post('/articles/{id}/restore', [ArticleController::class, 'restore'])->name('articles.restore');

    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
 

    Route::get('/berita/{id}/edit', [NewsController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [NewsController::class, 'update'])->name('berita.update');
    Route::patch('/berita/{news}/publish', [NewsController::class, 'publish'])->name('berita.publish');
    Route::patch('/berita/{news}/unpublish', [NewsController::class, 'unpublish'])->name('berita.unpublish');
    Route::patch('/berita/{news}/archive', [NewsController::class, 'archive'])->name('berita.archive');
    Route::delete('/berita/{id}/force-delete', [NewsController::class, 'forceDelete'])->name('berita.force-delete');
    Route::post('/berita/{id}/restore', [NewsController::class, 'restore'])->name('berita.restore');

   
    
    Route::resource('berita', NewsController::class)->except(['show']);

Route::get('/berita/{news:slug}', [NewsController::class, 'show'])
    ->name('berita.show');
    

    // Additional routes untuk permission management
    Route::post('/permissions/{permission}/assign-role', [PermissionController::class, 'assignToRole'])
        ->name('permissions.assign-role');
    Route::delete('/permissions/{permission}/revoke-role/{role}', [PermissionController::class, 'revokeFromRole'])
        ->name('permissions.revoke-role');
    Route::resource('permissions', PermissionController::class);

    // Articles Routes - TAMBAHKAN INI
    Route::get('/articles', [DashboardController::class, 'articles'])->name('articles.index');
    Route::get('/articles/create', [DashboardController::class, 'createArticle'])->name('articles.create');
    Route::post('/articles', [DashboardController::class, 'storeArticle'])->name('articles.store');
    Route::get('/articles/{article}/edit', [DashboardController::class, 'editArticle'])->name('articles.edit');
    Route::put('/articles/{article}', [DashboardController::class, 'updateArticle'])->name('articles.update');
    Route::delete('/articles/{article}', [DashboardController::class, 'destroyArticle'])->name('articles.destroy');
    Route::post('/upload-image', [DashboardController::class, 'uploadImage'])->name('upload.image');
    // Route::get('/category', [DashboardController::class, 'categories'])->name('category.index');
    Route::get('/category/{category}/edit', [DashboardController::class, 'editCategory'])->name('categories.edit');


    Route::get('/comments', [DashboardController::class, 'comments'])->name('comments.index');
    Route::post('/comments/{id}/approve', [DashboardController::class, 'approveComment'])->name('comments.index.approve');
    Route::delete('/comments/{id}/reject', [DashboardController::class, 'rejectComment'])->name('comments.index.reject');
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics.index');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
    Route::get('/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [DashboardController::class, 'destroyUser'])->name('users.destroy');
    // Routes untuk publish/unpublish
    Route::patch('/articles/{article}/publish', [DashboardController::class, 'publish'])->name('articles.publish');
    Route::patch('/articles/{article}/unpublish', [DashboardController::class, 'unpublish'])->name('articles.unpublish');
});


//tambah kode
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo.update');


    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token, 'email' => request('email')]);
    })->name('password.reset');
});

// =====================
// AUTH (GUEST)
// =====================
Route::middleware('guest')->group(function () {

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::post('/check-unique', [RegisteredUserController::class, 'checkUnique'])
        ->name('check.unique');
});

require __DIR__ . '/auth.php';
