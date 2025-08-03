<?php

use yii\db\Migration;

/**
 * Class m250802_000000_seed_vacancy
 */
class m250802_000000_seed_vacancy extends Migration
{
    public function safeUp()
    {
        $rows = [];
        $now = new DateTimeImmutable();

        for ($i = 1; $i <= 30; $i++) {
            $title = "Vacancy #{$i}";
            $description = $i % 3 === 0
                ? null // иногда без описания
                : "Описание вакансии #{$i}. " . str_repeat('Подробности... ', rand(2, 6));

            $salary = $i % 4 === 0
                ? null // иногда без зарплаты
                : rand(80_000, 350_000);

            $createdAt = $now
                ->modify("-{$i} days")
                ->format('Y-m-d H:i:s');

            $rows[] = [
                $title,
                $description,
                $salary ? $salary * 100 : null, // копейки
                $createdAt
            ];
        }

        $this->batchInsert('{{%vacancy}}',
            ['title', 'description', 'salary', 'created_at'],
            $rows
        );
    }

    public function safeDown()
    {
        $this->delete('{{%vacancy}}', ['like', 'title', 'Vacancy #']);
    }
}
