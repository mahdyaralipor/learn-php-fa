---
title: "پروژه: دفتر نمرات"
weight: 2
---

# پروژه: ساخت دفتر نمرات با OOP

در این پروژه یک دفتر نمرات ساده می‌سازیم تا مفاهیم OOP را روی مسئله‌ای واقعی‌تر تمرین کنی: دانش‌آموز داشته باشیم، برای هر دانش‌آموز نمره ثبت کنیم، میانگین بگیریم و گزارش چاپ کنیم.

## خروجی مورد انتظار

```php
<?php

$gradeBook = new GradeBook("کلاس PHP");

$student1 = new Student("علی");
$student1->addGrade(18.5);
$student1->addGrade(17);

$student2 = new Student("سارا");
$student2->addGrade(19);
$student2->addGrade(20);

$gradeBook->addStudent($student1);
$gradeBook->addStudent($student2);
$gradeBook->printReport();
```

## طراحی کلاس‌ها

در نسخهٔ ساده دو کلاس کافی‌اند: `Student` و `GradeBook`. `Student` نام و نمره‌ها را نگه می‌دارد و میانگین را حساب می‌کند. `GradeBook` لیست دانش‌آموزها را نگه می‌دارد و گزارش کلی می‌دهد.

## کلاس `Student`

```php
<?php

class Student
{
    private array $grades = [];

    public function __construct(
        public readonly string $name
    ) {
    }

    public function addGrade(float $grade): void
    {
        if ($grade < 0 || $grade > 20) {
            throw new InvalidArgumentException("نمره باید بین ۰ تا ۲۰ باشد.");
        }

        $this->grades[] = $grade;
    }

    public function getGrades(): array
    {
        return $this->grades;
    }

    public function average(): float
    {
        if ($this->grades === []) {
            return 0.0;
        }

        return array_sum($this->grades) / count($this->grades);
    }

    public function reportLine(): string
    {
        return "{$this->name} - میانگین: " . number_format($this->average(), 2);
    }
}
```

`grades` را `private` گذاشتیم تا نمره‌ها فقط از مسیر کنترل‌شدهٔ `addGrade()` وارد شوند.

## کلاس `GradeBook`

```php
<?php

class GradeBook
{
    private array $students = [];

    public function __construct(public string $title)
    {
    }

    public function addStudent(Student $student): void
    {
        $this->students[] = $student;
    }

    public function getStudents(): array
    {
        return $this->students;
    }

    public function printReport(): void
    {
        echo "دفتر نمرات: {$this->title}" . PHP_EOL;
        echo str_repeat('-', 30) . PHP_EOL;

        foreach ($this->students as $student) {
            echo $student->reportLine() . PHP_EOL;
        }
    }
}
```

نکتهٔ مهم این است که `GradeBook` خودش نمره‌ها را مدیریت نمی‌کند. این مسئولیت روی دوش `Student` است.

## اجرای کامل

```php
<?php

$gradeBook = new GradeBook("کلاس برنامه‌نویسی");

$ali = new Student("علی");
$ali->addGrade(18.5);
$ali->addGrade(16.75);
$ali->addGrade(19);

$sara = new Student("سارا");
$sara->addGrade(20);
$sara->addGrade(19.5);

$gradeBook->addStudent($ali);
$gradeBook->addStudent($sara);
$gradeBook->printReport();
```

خروجی تقریبی:

```text
دفتر نمرات: کلاس برنامه‌نویسی
------------------------------
علی - میانگین: 18.08
سارا - میانگین: 19.75
```

## قابلیت اضافه: دانش‌آموز برتر

```php
<?php

public function topStudent(): ?Student
{
    if ($this->students === []) {
        return null;
    }

    $top = $this->students[0];

    foreach ($this->students as $student) {
        if ($student->average() > $top->average()) {
            $top = $student;
        }
    }

    return $top;
}
```

## توسعه‌های خوب

- ساخت `StudentStatus` به شکل enum برای قبولی یا مردودی
- ساخت exception سفارشی مثل `InvalidGradeException`
- ذخیرهٔ داده در JSON
- namespaceدار کردن کلاس‌ها
- نوشتن تست برای `average()`

## اشتباهات رایج

1. `public` کردن همهٔ propertyها و از دست‌دادن کنترل اعتبار داده
2. قرار دادن تمام منطق در `GradeBook`
3. نداشتن validation برای نمره‌های نامعتبر
4. برگرداندن مقدار مبهم بدون فکر

## تمرین‌های توسعه

1. متدی به `Student` اضافه کن که بالاترین نمره را برگرداند.
2. متدی به `GradeBook` اضافه کن که میانگین کل کلاس را حساب کند.
3. اجازه نده دانش‌آموز با نام خالی ساخته شود.
4. متد حذف دانش‌آموز اضافه کن.
5. گزارش را طوری تغییر بده که همهٔ نمره‌های هر دانش‌آموز هم چاپ شود.

## جمع‌بندی

این پروژه کوچک است، اما چند اصل مهم OOP را تمرین می‌دهد: تقسیم مسئولیت، استفاده از `private` برای محافظت از state، validation، type hint و طراحی قابل‌گسترش.
