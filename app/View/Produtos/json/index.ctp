<?php
for ($i=0; $i<count($posts); $i++)
{
    unset($posts[$i]["Post"]["created"]);
    unset($posts[$i]["Post"]["modified"]);
}
json_encode($posts);
?>