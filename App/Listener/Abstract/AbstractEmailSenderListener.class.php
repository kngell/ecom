<?php

declare(strict_types=1);

use Pelago\Emogrifier\CssInliner;
use Pelago\Emogrifier\HtmlProcessor\HtmlNormalizer;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

abstract class AbstractEmailSenderListener
{
    protected View $view;
    protected CssToInlineStyles $inlineCssClass;

    protected function getMessage(EmailSenderConfiguration $emailConfig) :string
    {
        $this->view->path($emailConfig->getRootPath())->webView(false)->layout($emailConfig->getLayout());
        $html = $this->view->render($emailConfig->getEmailTemplate());
        $cssInliner = CssInliner::fromHtml($html)->inlineCss($emailConfig->getCssPath());
        HtmlPruner::fromDomDocument($cssInliner->getDomDocument())
            ->removeRedundantClassesAfterCssInlined($cssInliner);
        // //$html = $this->inlineCssClass->convert($html, $emailConfig->getCssPath());
        return utf8_decode(HtmlNormalizer::fromHtml($cssInliner->render())->render());
    }

    // private function parseDom(string $html) : string
    // {
    //     // $html = str_replace('&nbsp;', ' ', $html);
    //     return utf8_decode($html);
    // }
}