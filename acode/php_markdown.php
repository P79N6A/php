<?php
ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 为上海时区
require("../vendor/autoload.php");
require("./Parsedown.php");

use Michelf\Markdown;
$my_text  = "你好";
$markdown = "hello world";
$my_html  = Markdown::defaultTransform($my_text);
$parser   = new \cebe\markdown\Markdown();
echo $parser->parse($markdown);

// use github markdown
$parser = new \cebe\markdown\GithubMarkdown();
echo $parser->parse($markdown);

// use markdown extra
$parser = new \cebe\markdown\MarkdownExtra();
echo $parser->parse($markdown);

// parse only inline elements (useful for one-line descriptions)
$parser = new \cebe\markdown\GithubMarkdown();
echo $parser->parseParagraph($markdown);

$parsedown = new Parsedown();

$parsedown->setSafeMode(true);
echo $parsedown->text('Hello _Parsedown_!'); # prints: <p>Hello <em>Parsedown</em>!</p>
// you can also parse inline markdown only
echo $parsedown->line('Hello _Parsedown_!'); # prints: Hello <em>Parsedown</em>!