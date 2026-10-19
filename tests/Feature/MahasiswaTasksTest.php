<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_filter_tasks_by_status_course_and_deadline(): void
    {
        $student = $this->makeStudent('student@example.test');
        $course = $this->makeCourse();
        $matchingTask = $this->makeTask($student, $course, 'Tugas hari ini', 'belum', today()->setTime(23, 0));
        $this->makeTask($student, $course, 'Tugas selesai', 'selesai', today()->setTime(22, 0));

        $response = $this->actingAs($student)->get(route('mahasiswa.tasks.index', [
            'q' => 'hari ini',
            'status' => 'belum',
            'course_id' => $course->id,
            'deadline' => 'hari_ini',
        ]));

        $response->assertOk()
            ->assertSee($matchingTask->judul)
            ->assertDontSee('Tugas selesai');
    }

    public function test_student_can_create_update_status_and_delete_own_task(): void
    {
        $student = $this->makeStudent('crud@example.test');
        $course = $this->makeCourse();
        $this->actingAs($student);

        $this->post(route('mahasiswa.tasks.store'), [
            'judul' => 'Tugas dibuat',
            'deskripsi' => 'Deskripsi tugas',
            'course_id' => $course->id,
            'deadline' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'belum',
        ])->assertRedirect(route('mahasiswa.tasks.index'));

        $task = Task::where('judul', 'Tugas dibuat')->firstOrFail();

        $this->patch(route('mahasiswa.tasks.status', $task), ['status' => 'proses'])
            ->assertRedirect(route('mahasiswa.tasks.index'));
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'proses']);

        $this->delete(route('mahasiswa.tasks.destroy', $task))
            ->assertRedirect(route('mahasiswa.tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_student_cannot_view_another_students_task(): void
    {
        $owner = $this->makeStudent('owner@example.test');
        $anotherStudent = $this->makeStudent('another@example.test');
        $task = $this->makeTask($owner, $this->makeCourse(), 'Private task');

        $this->actingAs($anotherStudent)
            ->get(route('mahasiswa.tasks.show', $task))
            ->assertNotFound();
    }

    public function test_missing_page_uses_the_custom_error_screen(): void
    {
        config(['app.debug' => false]);

        $this->get('/halaman-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan')
            ->assertSee('StudyMate');
    }

    private function makeStudent(string $email): User
    {
        return User::create([
            'name' => 'Mahasiswa',
            'email' => $email,
            'nim' => str_replace(['@', '.'], '', $email),
            'password' => 'password',
            'role' => 'mahasiswa',
        ]);
    }

    private function makeCourse(): Course
    {
        return Course::create([
            'kode' => 'IF301-'.fake()->unique()->numerify('###'),
            'nama' => 'Pemrograman Web',
            'sks' => 3,
            'dosen' => 'Dosen Contoh',
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'ruangan' => 'Lab 1',
        ]);
    }

    private function makeTask(User $student, Course $course, string $title, string $status = 'belum', $deadline = null): Task
    {
        return $student->tasks()->create([
            'course_id' => $course->id,
            'judul' => $title,
            'deskripsi' => null,
            'deadline' => $deadline ?? now()->addDay(),
            'status' => $status,
        ]);
    }
}
