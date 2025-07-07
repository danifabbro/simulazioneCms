<?php

    // funzione escape per xss injection
    function html_escape(string $string): string
    {                        
        return htmlspecialchars($string,ENT_QUOTES|ENT_HTML5,'UTF-8',true);                        
    }

    function format_date(string $string): string
    {
        $date = strtotime($string);
        return date('F d, Y', $date);
    }

    function pdo(PDO $pdo, string $sql, array $arguments = null)
    {
        if(!$arguments)
        {
            return $pdo->query($sql); // ESEGUE LA QUERY SE GLI ARGOMENTI SONO DIVERSI DA NULL
        }
        $statement = $pdo->prepare($sql);
        $statement->execute($arguments);
        return $statement;
    }

    function is_text($text, int $min = 0, int $max = 1000): bool
    {
        $length = mb_strlen($text);
        return ($length >= $min and $length <= $max);
    }

    function redirect(string $location, array $parameters = [], $response_code = 302)
    {
        $qs = $parameters ? '?' . http_build_query($parameters) : '';
        $location = $location . $qs;
        header('Location: ' . $location , $response_code);
        exit;
    }

    function is_account_id($member_id, array $member_list): bool
    {
        foreach ($member_list as $member) {
            if ($member['id'] == $member_id) {
                return true;
            }
        }
        return false;
    }

    function is_categoria_id($category_id, array $category_list): bool
    {
        foreach ($category_list as $category) {
            if ($category['id'] == $category_id) {
                return true;
            }
        }
        return false;
    }    

    function create_filename($filename, $upload_path)
    {
        $base = pathinfo($filename, PATHINFO_FILENAME); // nome senza estensione
        $extension = pathinfo($filename, PATHINFO_EXTENSION); // estensione
        $basename = preg_replace('/[^A-z0-9]/', '-', $basename); // pulizia nome
        
        $i=0;
      
        while (file_exists($upload_path . $filename)) {
            $i = $i + 1;
            $filename = $basename . $i . '.' . $extension;//aggiungiamo un 1 per renderlo sempre unico come file
        }
        return $filename;
    }   

?>