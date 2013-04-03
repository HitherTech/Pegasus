<?php
namespace Pegasus\Core;

/**
 * Default View handler.
 *
 * @see https://github.com/desfrenes/Shozu/blob/master/View.php
 */
class View {
    /**
     * File.
     *
     * @var string
     */
    protected $file;

    /**
     * Variables assigned to global view scope.
     *
     * @var array
     */
    protected $vars;

    /**
     * Assign the template path
     *
     *
     * @param string $file Template path (absolute path or path relative to the templates dir)
     * @param array|bool $vars assigned variables
     * @throws \Exception
     */
    public function __construct($fileName, $vars = false) {
        $this->file = (string) $fileName . '.phtml';

        if (!file_exists($this->file)) {
            throw new \Exception('View error: ' . $this->file  .' not found!');
        }

        if ($vars !== false) {
            $this->vars = $vars;
        }
    }

    /**
     * Assign specific variable to the template.
     *
     * @param mixed $name Variable name
     * @param mixed $value Variable value
     */
    public function assign($name, $value = null) {
        if (is_array($name)) {
            array_merge($this->vars, $name);
        } else {
            $this->vars[$name] = $value;
        }
    }

    /**
     * Return template output as string.
     *
     * @param bool $stripSpace
     * @return string content of compiled view template
     */
    public function render($stripSpace = false) {
        ob_start();
        if (is_array($this->vars)) {
            extract($this->vars, EXTR_SKIP);
        }
        require_once $this->file;
        $content = ob_get_clean();
        if ($stripSpace) {
            $content = self::stripSpace($content);
        }

        return $content;
    }

    /**
     * Strips away space characters to better compress view output.
     *
     * @param string $html
     * @return string
     */
    public static function stripSpace($html) {
        return preg_replace('#(?:(?:(^|>[^<]*?)[\t\s\r\n]*)|(?:[\t\s\r\n]*(<|$)))#', '$1$2', $html);
    }

    /**
     * Escape HTML special chars.
     *
     * @param string
     * @return string
     */
    public function escape($string) {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return htmlspecialchars($string, ENT_QUOTES | ENT_HTML401, 'utf-8', true);
        }

        return htmlspecialchars($string, ENT_QUOTES, 'utf-8', true);
    }
}