<?php namespace Flaviozantut\Todo\Providers;

class Markdown  implements  ProvidersInterface {

    public $file;

    public function __construct($file)
    {
        $this->endTodo = "<!---END TODO LIST TOTAL=";
        $this->file = $file;
        if (!file_exists($file)) {
            $data = "# to-do\n\n".$this->endTodo."0-->";
            file_put_contents ($file, $data);
        }
    }

    public function getContent()
    {
        return file_get_contents($this->file);
    }

    public function add($todo)
    {
        $data = preg_replace_callback(
            "/(".$this->endTodo.")(\d+)/",
            function($p) use($todo){
                return ($p[2]+1) . ". " . $todo."\n". $p[1] . ($p[2]+1);
            },
            $this->getContent()
        );
        file_put_contents ($this->file, $data);
        return $this->last();
    }

    public function ArrayOfAll()
    {
        preg_match_all(
            "/\n(\d+)\.\s(.*)\n/x",
            $this->getContent(),
            $result
        );
        $allTodos = array();
        foreach ($result[1] as $key=>$row) {
            $allTodos[$row] = $result[2][$key];
        }
        return $allTodos;
    }

    public function last()
    {
        $arr = $this->ArrayOfAll();
        return array(
            'id'=> array_search(end($arr), $arr),
            'todo'=>end($arr)
        );
    }

    public function get($todo)
    {
        $arr = $this->ArrayOfAll();
        return  $arr[$todo];
    }

    public function complete($todo)
    {
        $todoText = $this->get($todo);

        $data = preg_replace_callback(
            "/\n(". $todo .")\.\s(" . $todoText . ")\n/",
            function($p){
                return "\n". $p[1] .". <strike>" . $p[2] . "</strike>\n";
            },
            $this->getContent()
        );
        file_put_contents ($this->file, $data);
        return $this->get($todo);

    }

    public function status($todo)
    {
        $todoText = $this->get($todo);
        if (preg_match("/^<strike>/", $todoText)) {
            return 'closed';
        } else {
            return 'opening';
        }
    }

    public function listing()
    {

        $list = array();
        foreach ($this->ArrayOfAll() as $key => $value) {
            $list[$key]['id'] = $key;
            $list[$key]['todo'] = preg_replace("/^\<strike\>(.*)\<\/strike\>$/", "$1", $value);
            $list[$key]['status'] = $this->status($key);
        }
        return $list;
    }

    public function reopen($todo)
    {
        $todoText = strip_tags($this->get($todo));
        $data = preg_replace_callback(
           "/\n($todo)\.\s(\<strike\>$todoText\<\/strike\>)\n/",
            function($p){
                $text = preg_replace('/<strike>(.*)<\/strike>/', "$1", $p[2]);
                return "\n". $p[1] .". " . $text . "\n";
            },
            $this->getContent()
        );
        file_put_contents ($this->file, $data);
        return $this->get($todo);
    }

    public function delete($todo)
    {
        $todoText = $this->get($todo);
        $data = preg_replace(
           "/\n($todo)\.\s.*\n/",
            '',
            $this->getContent()
        );
        file_put_contents ($this->file, $data);
        return $this->get($todo);
    }
}