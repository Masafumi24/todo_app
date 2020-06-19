<?php

// namespace Tests\Feature;

// use App\Http\Requests\CreateTask;
// use Carbon\Carbon;
// use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;

// class TaskTest extends TestCase
// {
//     use RefreshDatabase;

//     public function setUp() :void
//     {
//         parent::setUp();
//         $this->seed('FoldersTableSeeder');
//     }

//     public function due_date_should_be_date()
//     {
//         $response = $this->post('/folders/1/tasks/create', [
//             'title' => 'Sample task',
//             'due_data' => 123,
//         ]);

//         $response->assertSessionHasErrors([
//             'due_data' => '期限日には日付を入力してください'
//         ]);
//     }

//     public function due_date_should_not_be_past()
//     {
//         $response = $this->post('/folders/1/tasks/create', [
//             'title' => 'Sample task',
//             'due_data' => Carbon::yesterday()->format('Y/m/d'),
//         ]);

//         $response->assertSessionHasErrors([
//             'due_data' => '期限日 には今日以降の日付を入力してください。'
//         ]);
//     }
// } 

namespace Tests\Feature;

use App\Http\Requests\CreateTask;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    // テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    /**
     * 各テストメソッドの実行前に呼ばれる
     */
    public function setUp() :void
    {
        parent::setUp();

        $this->seed('FoldersTableSeeder');
    }

    /**
     * @test
     */
    public function due_dat_should_be_date()
    {
        $response = $this->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_data' => 123,
        ]);

        $response->assertSessionHasErrors([
            'due_data' => '期限日 には日付を入力してください。',
        ]);
    }

    /**
     * @test
     */
    public function due_data_should_not_be_past()
    {
        $response = $this->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_data' => Carbon::yesterday()->format('Y/m/d'), // 不正なデータ（昨日の日付）
        ]);

        $response->assertSessionHasErrors([
            'due_data' => '期限日 には今日以降の日付を入力してください。',
        ]);
    }
}