<?php
namespace WScore\Template;

class Renderer
{
    /** specify self rendering mode.  */
    private $self = false;

    // +----------------------------------------------------------------------+
    //  rendering the template.
    // +----------------------------------------------------------------------+
    /**
     * @param string $template
     * @param array  $parameters
     * @throws \RuntimeException
     * @return mixed
     */
    public function render( $template, $parameters = array() )
    {
        // attach the global variables
        if( $this->self ) {
            $content = ob_get_clean();
        }
        else {
            $content = $this->evaluate( $template, $parameters );
        }
        return $content;
    }

    /**
     * rendering output from own php file.
     */
    public function renderSelf() {
        $this->self = true;
        ob_start();
    }

    /**
     * Evaluates a template.
     *
     * @param string  $template   The template to render
     * @param array   $parameters An array of parameters to pass to the template
     * @return string|bool The evaluated template, or false if the engine is unable to render the template
     */
    protected function evaluate( $template, array $parameters = array())
    {
        $parameters[ '__template__' ] = $template;

        /** @var $__template__ string */
        extract( $parameters, EXTR_SKIP );
        ob_start();
        require $__template__;

        return ob_get_clean();
    }

    /**
     * render output if rendering self output.
     */
    public function __destruct()
    {
        if( $this->self ) {
            echo $this->render( $this->templateFile );
        }
    }
}