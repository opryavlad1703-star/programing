# Звіт з Лабораторної роботи №10

## 1. Код класу Task (Task.php)
Клас описує сутність завдання, містить приватні властивості, бізнес-методи для перевірки статусу та методи серіалізації в масив/з масиву.

```php
<?php
declare(strict_types=1);

class Task
{
    public function __construct(
        public readonly int $id,
        private string $title,
        private bool $isCompleted = false,
        private ?string $deadline = null,
    ) {
        if (empty(trim($title))) {
            throw new InvalidArgumentException('Назва завдання не може бути порожньою.');
        }
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function isCompleted(): bool { return $this->isCompleted; }
    public function getDeadline(): ?string { return $this->deadline; }

    public function isOverdue(): bool
    {
        if ($this->isCompleted || $this->deadline === null) {
            return false;
        }
        return strtotime($this->deadline) < time();
    }

    public function complete(): void { $this->isCompleted = true; }

    public function getStatusLabel(): string
    {
        if ($this->isCompleted) return 'Виконано';
        if ($this->isOverdue()) return 'Прострочено';
        return 'В процесі';
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'isCompleted' => $this->isCompleted,
            'deadline'    => $this->deadline,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id:          $data['id'],
            title:       $data['title'],
            isCompleted: $data['isCompleted'] ?? false,
            deadline:    $data['deadline']    ?? null,
        );
    }
}
```
## 4. Контрольні запитання

1. **Що таке клас і чим він відрізняється від звичайного масиву у PHP? Чому завдання зручніше представити об'єктом класу Task, ніж масивом `['id' => 1, 'title' => '...']`?**
   Клас — це шаблон, який об'єднує дані (властивості) та поведінку (методи). Об'єкт дає сувору типізацію, захищає дані від некоректного внесення та містить у собі бізнес-логіку (наприклад, автоматичне визначення простроченості), тоді як масив — це лише розрізнений набір даних без вбудованих правил.

2. **Що таке `private`-властивість і навіщо `$title` у класі Task зроблено `private`? Як зовнішній код може прочитати її значення?**
   `private` обмежує доступ до властивості лише всередині самого класу. Це реалізує принцип інкапсуляції, захищаючи поле від випадкової або некоректної прямої зміни ззовні. Прочитати значення можна через публічний метод-гетер `getTitle()`.

3. **Поясніть, що відбувається у рядку `public readonly int $id` у конструкторі. Чому `$id` є `readonly`, а `$isCompleted` — ні?**
   Синтаксис у конструкторі скорочено оголошує властивість і присвоює їй значення. Модифікатор `readonly` гарантує, що унікальний ідентифікатор можна встановити лише один раз під час створення об'єкта, і він не зможе випадково змінитися. Стан `$isCompleted` не є `readonly`, оскільки завдання змінює свій статус під час виконання.

4. **Що робить статичний метод `Task::fromArray($data)`? Для чого він оголошений як `static`, а не як звичайний метод?**
   Цей метод приймає асоціативний масив (зчитаний з JSON-файлу) і конструює з нього новий об'єкт класу `Task`. Він оголошений як `static`, тому що викликається безпосередньо у класу (без наявності вже створеного об'єкта) для створення нового екземпляра.

5. **Опишіть «ланцюжок» збереження даних: що відбувається під капотом від моменту виклику `$repo->add('Нове завдання')` до появи нового рядка у файлі `tasks.json`?**
   * Метод `add()` генерує новий унікальний ID та створює новий об'єкт `Task`.
   * Новий об'єкт додається до внутрішнього масиву `$tasks` репозиторію.
   * Автоматично викликається приватний метод `save()`.
   * Метод `save()` викликає `$t->toArray()` для кожного об'єкта в масиві, перетворюючи їх на асоціативні масиви.
   * Масив кодується у JSON-рядок за допомогою `json_encode()`.
   * Рядок записується на диск у файл `tasks.json` за допомогою `file_put_contents()`.