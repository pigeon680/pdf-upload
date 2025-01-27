<?php
// Database connection (using PDO)
class Database
{
    private $pdo;

    public function __construct($dbFile = 'tasks.sqlite')
    {
        $this->pdo = new PDO('sqlite:' . $dbFile);
        $this->initialize();
    }

    private function initialize()
    {
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                completed INTEGER DEFAULT 0
            )
        ');
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}

// Task Model
class Task
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create a new task
    public function createTask($title, $description)
    {
        $stmt = $this->db->prepare('INSERT INTO tasks (title, description) VALUES (:title, :description)');
        $stmt->execute(['title' => $title, 'description' => $description]);
        return $this->db->lastInsertId();
    }

    // Read all tasks
    public function getTasks()
    {
        $stmt = $this->db->query('SELECT * FROM tasks ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a task
    public function updateTask($id, $title, $description, $completed)
    {
        $stmt = $this->db->prepare('
            UPDATE tasks SET title = :title, description = :description, completed = :completed WHERE id = :id
        ');
        return $stmt->execute([
                                  'id'          => $id,
                                  'title'       => $title,
                                  'description' => $description,
                                  'completed'   => $completed
                              ]);
    }

    // Delete a task
    public function deleteTask($id)
    {
        $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}

// Example Usage
try {
    $db          = (new Database())->getConnection();
    $taskManager = new Task($db);

    // Create a new task
    $taskManager->createTask('Learn PHP', 'Build a CRUD app to understand PDO and SQLite.');

    // Get all tasks
    $tasks = $taskManager->getTasks();
    echo "Tasks:\n";
    foreach ($tasks as $task) {
        echo " - {$task['title']} (Completed: {$task['completed']})\n";
    }

    // Update a task
    if (!empty($tasks)) {
        $taskManager->updateTask($tasks[0]['id'], 'Learn Advanced PHP', 'Improve the CRUD app.', 1);
    }

    // Delete a task
    if (!empty($tasks)) {
        $taskManager->deleteTask($tasks[0]['id']);
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>