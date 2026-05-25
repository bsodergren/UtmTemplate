<?php

declare(strict_types=1);

namespace UTMTemplate\Bundle\Carousel\Renderer;

use UTMTemplate\Bundle\Carousel\Carousel;

/**
 * Interface for all renderers
 */
interface RendererInterface
{
    /**
     * Render the carousel
     *
     * @param Carousel $carousel The carousel to render
     * @return string Rendered output
     */
    public function render(Carousel $carousel): string;
}

