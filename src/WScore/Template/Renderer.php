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
     * @param array  $_parameters
     * @throws \RuntimeException
     * @return mixed
     */
    public function render( $template, $_parameters = array() )
    {
        $_parameters[ '__template__' ] = $template;

        /** @var $__template__ string */
        extract( $_parameters, EXTR_SKIP );
        ob_start();
        require $__template__;

        return ob_get_clean();
    }

    /**
     * rendering output from own php file.
     */
    public function renderSelf() {
        $this->self = true;
        ob_start();
    }

    /**
     * render output if rendering self output.
     */
    public function __destruct()
    {
        if( $this->self ) {
            echo ob_get_clean();
        }
    }
}