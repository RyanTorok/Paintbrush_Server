<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 9/28/18
 * Time: 2:57 PM
 */

class Identifier {
    private $name;
    private $description;
    private $type;
    private $classId;
    private $id;
    private $authorName;
    private $time1;
    private $time2;
    private $views;
    private $likes;

    public function __construct($typ, $id, $connection)
    {
        $this->type = $typ;
        switch ($typ) {
            case "classItem": {
                $row = $this->getResults($connection, 'classItems', $id);
                $this->name = $row[1];
                $this->classId = $row[4];
                $this->time1 = getdate($row[5]);
                $this->time2 = getdate($row[6]);
                break;
            }
            case "module": {
                //TODO
                break;
            }
            case "post": {
                $row = $this->getResults($connection, 'posts', $id);
                $this->name = $row[4];
                $this->description = $row[5];
                $this->time1 = getdate($row[10]);
                $this->time2 = getdate($row[11]);

                $stmt = $connection->prepare("SELECT COUNT(*) FROM ? WHERE postId = ?");
                $stmt->bind_param('si', 'likes', $id);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_row();
                $this->likes = $row[0];

                $stmt = $connection->prepare("SELECT COUNT(*) FROM ? WHERE postId = ?");
                $stmt->bind_param('si', 'views', $id);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_row();
                $this->views = $row[0];

                break;
            }
            case "organization":
            case "class": {
                $row = $this->getResults($connection, $typ === 'class' ? 'classes' : 'organizations', $id);
                $this->authorName = $row[2] . ' ' . $row[3];
                $this->$this->description = $row[6];
                break;
            }
        }
    }

    public function toString($relevance) {
        return $this->escape($this->name) . ';' . $this->escape($this->description) . ';' . $this->escape($this->type) . ';'
            . $this->escape($this->classId) . ';' . $this->escape($this->authorName) . ';' . $this->escape($this->time1) . ';'
            . $this->escape($this->time2) . ';' . $this->escape($this->views) . ';' . $this->escape($this->likes) . ';' . $relevance;
    }

    public function escape($str) {
        return str_replace(";", "\\;", str_replace("\\", "\\\\", $str));
    }

    private function getResults($conn, $tableName, $id) {
        $stmt = $conn->prepare("SELECT * FROM ? WHERE id = ?");
        $stmt->bind_param('si', $tableName, $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_row();
        return $row;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return mixed
     */
    public function getTime1()
    {
        return $this->time1;
    }

    /**
     * @return mixed
     */
    public function getTime2()
    {
        return $this->time2;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


}
