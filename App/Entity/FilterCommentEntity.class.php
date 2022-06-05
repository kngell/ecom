<?php

declare(strict_types=1);

class FilterCommentEntity extends Entity
{
    /** @id */
    private int $fltID;
    private string $word;
    private string $replacement;

    /**
     * Get the value of fltID.
     */
    public function getFltID() : int
    {
        return $this->fltID;
    }

    /**
     * Set the value of fltID.
     *
     * @return  self
     */
    public function setFltID(int $fltID) : self
    {
        $this->fltID = $fltID;
        return $this;
    }

    /**
     * Get the value of word.
     */
    public function getWord() : string
    {
        return $this->word;
    }

    /**
     * Set the value of word.
     *
     * @return  self
     */
    public function setWord(string $word) : self
    {
        $this->word = $word;
        return $this;
    }

    /**
     * Get the value of replacement.
     */
    public function getReplacement() : string
    {
        return $this->replacement;
    }

    /**
     * Set the value of replacement.
     *
     * @return  self
     */
    public function setReplacement(string $replacement) : self
    {
        $this->replacement = $replacement;
        return $this;
    }
}