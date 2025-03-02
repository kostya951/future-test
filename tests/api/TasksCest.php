<?php

declare(strict_types=1);


namespace Api;

use \ApiTester;
use app\tests\fixtures\TaskFixture;
use Codeception\Module\Db;
use Codeception\Template\Api;

final class TasksCest
{
    public function _before(ApiTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function tryToConnect(ApiTester $I): void
    {
        $I->sendGet('/tasks');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToGetAll(ApiTester $I): void {
        $I->sendGet('/tasks');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $actual = $I->grabDataFromResponseByJsonPath('$')[0][0];
        $expected = $I->grabFixture('tasks',0);
        $I->assertEquals($expected->id,$actual['id']);
        $I->assertEquals($expected->name,$actual['name']);
        $I->assertEquals($expected->description,$actual['description']);
        $I->assertEquals($expected->due_date,$actual['due_date']);
        $I->assertEquals($expected->create_date,$actual['create_date']);
        $I->assertEquals($expected->status,$actual['status']);
        $I->assertEquals($expected->category,$actual['category']);
        $I->assertEquals($expected->priority,$actual['priority']);
    }

    public function tryToGetAllWithSearch(ApiTester $I): void{
        $I->sendGet('/tasks',['search' => 'ключ']);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0][0];
        $expected = $I->grabFixture('tasks',3);

        $I->assertEquals($expected->id,$actual['id']);
        $I->assertEquals($expected->name,$actual['name']);
        $I->assertEquals($expected->description,$actual['description']);
        $I->assertEquals($expected->due_date,$actual['due_date']);
        $I->assertEquals($expected->create_date,$actual['create_date']);
        $I->assertEquals($expected->status,$actual['status']);
        $I->assertEquals($expected->category,$actual['category']);
        $I->assertEquals($expected->priority,$actual['priority']);
    }

    public function tryToGetAllWithSortDueDate(ApiTester $I): void{
        $I->sendGet('/tasks',['sort'=>'due_date']);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0][0];
        $expected = $I->grabFixture('tasks',3);

        $I->assertEquals($expected->due_date, $actual['due_date']);
    }

    public function tryToGetAllWithSortAndSearch(ApiTester $I){
        $I->sendGet('/tasks', ['sort'=>'due_date','search'=>'task']);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0][0];
        $expected = $I->grabFixture('tasks',2);

        $I->assertEquals($expected['id'],$actual['id']);
    }

    public function tryToCreateTaskWithoutName(ApiTester $I){
        $I->sendPostAsJson('/tasks',[
            'description'=>'Invalid task',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0];

        $I->assertFalse($actual['ok']);
        $I->assertEquals('Name cannot be blank.', $actual['message']['name'][0]);
    }

    public function tryToCreateTask(ApiTester $I){
        $I->sendPostAsJson('/tasks',[
            'name' => 'Task5',
            'description'=> 'Description task5'
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0];

        $I->assertEquals(5, $actual['id']);
        $I->assertEquals('Task was created successfully', $actual['message']);

        $I->seeInDatabase('tasks',[
            'id' => 5,
            'name' => 'Task5',
            'description' => 'Description task5'
        ]);

    }

    public function tryToCreateTaskWihInvalidStatus(ApiTester $I){
        $I->sendPostAsJson('/tasks',[
            'name' => 'Task6',
            'description'=> 'Description task6',
            'status' => 'status'
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0];

        $I->assertFalse($actual['ok']);
        $I->assertEquals('Status is invalid.', $actual['message']['status'][0]);
    }

    public function tryToCreateTaskWihValidStatus(ApiTester $I){
        $I->sendPostAsJson('/tasks',[
            'name' => 'Task7',
            'description'=> 'Description task7',
            'status' => 'выполнена'
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0];

        $I->assertEquals(5,$actual['id']);
    }

    public function tryToGetById(ApiTester $I){
        $I->sendGet('tasks/1');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0];
        $expected = $I->grabFixture('tasks',0);

        $I->assertEquals($expected->id, $actual['id']);
    }

    public function tryToUpdateById(ApiTester $I){
        $I->sendPutAsJson('/tasks/1',[
            'status'=>'выполнена'
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $actual = $I->grabDataFromResponseByJsonPath('$')[0];
        $expected = $I->grabFixture('tasks',0);

        $I->assertEquals('Task updated successfully', $actual['message']);

        $I->seeInDatabase('tasks',[
            'id' => $expected['id'],
            'status'=>'выполнена'
        ]);
    }



    public function _fixtures(){
        return [
            'tasks' => TaskFixture::class,
        ];
    }
}
