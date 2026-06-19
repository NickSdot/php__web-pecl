<?php

namespace App\Utils;

use App\Database;

/**
 * Breadcrumbs generator for packages and categories.
 */
readonly class Breadcrumbs
{
    /**
     * Class constructor.
     */
    public function __construct(
        private Database $database
    ) {}

    /**
     * Get breadcrumbs for categories and packages.
     * Top Level :: Multimedia :: Audio :: FliteTTS
     *
     * @param int $id
     */
    public function getBreadcrumbs($id, bool $isLastLink = false): string
    {
        $html = '<a href="/packages.php">Top Level</a>';

        if (null !== $id) {
            $sql = "SELECT c.id, c.name
                    FROM categories c, categories cat
                    WHERE cat.id = :id
                        AND c.cat_left <= cat.cat_left
                        AND c.cat_right >= cat.cat_right
            ";

            $results = $this->database->run($sql, [':id' => $id])->fetchAll();
            $nrows = count($results);

            $i = 0;
            $lastCategory = null;
            foreach ($results as $row) {
                $lastCategory = $row;

                if (!$isLastLink && $i >= $nrows -1) {
                    break;
                }

                $html .= ' :: <a href="/packages.php?catpid='.$row['id'].'&catname='.$row['name'].'">'.$row['name'].'</a>';
                $i++;
            }

            if (!$isLastLink && $lastCategory !== null) {
                $html .= ' :: <b>'.$lastCategory['name'].'</b>';
            }
        }

        return $html;
    }
}
