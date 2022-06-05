<?php

declare(strict_types=1);

class ProductsEntity extends Entity
{
    /** @id */
    private int $pdtId;
    private string $userSalt;
    private int $unitId;
    private string $title;
    private string $shortDescr;
    private string $descr;
    private string $regularPrice;
    private string $comparePrice;
    private string $costPerItem;
    private string $chargeTax;
    /** @media */
    private string $media;
    private string $sku;
    private string $barreCode;
    private string $trackQty;
    private string $continiousSell;
    private int $qty;
    private string $backBorder;
    private int $stockThreshold;
    private string $weight;
    private string $lenght;
    private string $width;
    private string $height;
    private string $size;
    private string $packageSize;
    private string $color;
    private int $shippingClass;
    private string $status;
    private int $warehouse;
    private int $company;
    private string $slug;
    /** @var DateTimeInterface */
    private DateTimeInterface $createdAt;
    /** @var DateTimeInterface */
    private DateTimeInterface $updatedAt;
    private int $deleted;

    public function __construct()
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    /**
     * Get the value of pdtID.
     */
    public function getPdtId() : int
    {
        return $this->pdtId;
    }

    /**
     * Set the value of pdtID.
     *
     * @return  self
     */
    public function setPdtId(int $pdtID) : self
    {
        $this->pdtId = $pdtID;
        return $this;
    }

    /**
     * Get the value of user_salt.
     */
    public function getUserSalt() : string
    {
        return $this->userSalt;
    }

    /**
     * Set the value of user_salt.
     *
     * @return  self
     */
    public function setUserSalt(string $userSalt) : self
    {
        $this->user_salt = $userSalt;
        return $this;
    }

    /**
     * Get the value of unitID.
     */
    public function getUnitId() : int
    {
        return $this->unitId;
    }

    /**
     * Set the value of unitID.
     *
     * @return  self
     */
    public function setUnitId(int $unitID) : self
    {
        $this->unitId = $unitID;
        return $this;
    }

    /**
     * Get the value of title.
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     *
     * @return  self
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the value of shortDescr.
     */
    public function getShortDescr() : string
    {
        return $this->shortDescr;
    }

    /**
     * Set the value of shortDescr.
     *
     * @return  self
     */
    public function setShortDescr(string $shortDescr) : self
    {
        $this->shortDescr = $shortDescr;
        return $this;
    }

    /**
     * Get the value of descr.
     */
    public function getDescr() : string
    {
        return $this->descr;
    }

    /**
     * Set the value of descr.
     *
     * @return  self
     */
    public function setDescr(string $descr) : self
    {
        $this->descr = $descr;
        return $this;
    }

    /**
     * Get the value of regularPrice.
     */
    public function getRegularPrice() : string
    {
        return $this->regularPrice;
    }

    /**
     * Set the value of regularPrice.
     *
     * @return  self
     */
    public function setRegularPrice(string $regularPrice) : self
    {
        $this->regularPrice = $regularPrice;
        return $this;
    }

    /**
     * Get the value of comparePrice.
     */
    public function getComparePrice() : string
    {
        return $this->comparePrice;
    }

    /**
     * Set the value of comparePrice.
     *
     * @return  self
     */
    public function setComparePrice(string $comparePrice) : self
    {
        $this->comparePrice = $comparePrice;
        return $this;
    }

    /**
     * Get the value of costPerItem.
     */
    public function getCostPerItem() : string
    {
        return $this->costPerItem;
    }

    /**
     * Set the value of costPerItem.
     *
     * @return  self
     */
    public function setCostPerItem(string $costPerItem) : self
    {
        $this->costPerItem = $costPerItem;
        return $this;
    }

    /**
     * Get the value of chargeTax.
     */
    public function getChargeTax() : string
    {
        return $this->chargeTax;
    }

    /**
     * Set the value of chargeTax.
     *
     * @return  self
     */
    public function setChargeTax(string $chargeTax) : self
    {
        $this->chargeTax = $chargeTax;
        return $this;
    }

    /**
     * Get the value of media.
     */
    public function getMedia() : string
    {
        return $this->media;
    }

    /**
     * Set the value of media.
     *
     * @return  self
     */
    public function setMedia(string $media) : self
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get the value of sku.
     */
    public function getSku() : string
    {
        return $this->sku;
    }

    /**
     * Set the value of sku.
     *
     * @return  self
     */
    public function setSku(string $sku) : self
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Get the value of barreCode.
     */
    public function getBarreCode() : string
    {
        return $this->barreCode;
    }

    /**
     * Set the value of barreCode.
     *
     * @return  self
     */
    public function setBarreCode(string $barreCode) : self
    {
        $this->barreCode = $barreCode;
        return $this;
    }

    /**
     * Get the value of trackQty.
     */
    public function getTrackQty() : string
    {
        return $this->trackQty;
    }

    /**
     * Set the value of trackQty.
     *
     * @return  self
     */
    public function setTrackQty(string $trackQty) : self
    {
        $this->trackQty = $trackQty;
        return $this;
    }

    /**
     * Get the value of continiousSell.
     */
    public function getContiniousSell() : string
    {
        return $this->continiousSell;
    }

    /**
     * Set the value of continiousSell.
     *
     * @return  self
     */
    public function setContiniousSell(string $continiousSell) : self
    {
        $this->continiousSell = $continiousSell;
        return $this;
    }

    /**
     * Get the value of qty.
     */
    public function getQty() : int
    {
        return $this->qty;
    }

    /**
     * Set the value of qty.
     *
     * @return  self
     */
    public function setQty(int $qty) : self
    {
        $this->qty = $qty;
        return $this;
    }

    /**
     * Get the value of backBorder.
     */
    public function getBackBorder() : string
    {
        return $this->backBorder;
    }

    /**
     * Set the value of backBorder.
     *
     * @return  self
     */
    public function setBackBorder(string $backBorder) : self
    {
        $this->backBorder = $backBorder;
        return $this;
    }

    /**
     * Get the value of stockThreshold.
     */
    public function getStockThreshold() : int
    {
        return $this->stockThreshold;
    }

    /**
     * Set the value of stockThreshold.
     *
     * @return  self
     */
    public function setStockThreshold(int $stockThreshold) : self
    {
        $this->stockThreshold = $stockThreshold;
        return $this;
    }

    /**
     * Get the value of weight.
     */
    public function getWeight() : string
    {
        return $this->weight;
    }

    /**
     * Set the value of weight.
     *
     * @return  self
     */
    public function setWeight(string $weight) : self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Get the value of lenght.
     */
    public function getLenght() : string
    {
        return $this->lenght;
    }

    /**
     * Set the value of lenght.
     *
     * @return  self
     */
    public function setLenght(string $lenght) : self
    {
        $this->lenght = $lenght;
        return $this;
    }

    /**
     * Get the value of width.
     */
    public function getWidth() : string
    {
        return $this->width;
    }

    /**
     * Set the value of width.
     *
     * @return  self
     */
    public function setWidth(string $width) : self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Get the value of height.
     */
    public function getHeight() : string
    {
        return $this->height;
    }

    /**
     * Set the value of height.
     *
     * @return  self
     */
    public function setHeight(string $height) : self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get the value of size.
     */
    public function getSize() : string
    {
        return $this->size;
    }

    /**
     * Set the value of size.
     *
     * @return  self
     */
    public function setSize(string $size) : self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get the value of packageSize.
     */
    public function getPackageSize() : string
    {
        return $this->packageSize;
    }

    /**
     * Set the value of packageSize.
     *
     * @return  self
     */
    public function setPackageSize(string $packageSize) : self
    {
        $this->packageSize = $packageSize;
        return $this;
    }

    /**
     * Get the value of color.
     */
    public function getColor() : string
    {
        return $this->color;
    }

    /**
     * Set the value of color.
     *
     * @return  self
     */
    public function setColor(string $color) : self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Get the value of shippingClass.
     */
    public function getShippingClass() : int
    {
        return $this->shippingClass;
    }

    /**
     * Set the value of shippingClass.
     *
     * @return  self
     */
    public function setShippingClass(int $shippingClass) : self
    {
        $this->shippingClass = $shippingClass;
        return $this;
    }

    /**
     * Get the value of status.
     */
    public function getStatus() : string
    {
        return $this->status;
    }

    /**
     * Set the value of status.
     *
     * @return  self
     */
    public function setStatus(string $status) : self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of warehouse.
     */
    public function getWarehouse() : int
    {
        return $this->warehouse;
    }

    /**
     * Set the value of warehouse.
     *
     * @return  self
     */
    public function setWarehouse(int $warehouse) : self
    {
        $this->warehouse = $warehouse;
        return $this;
    }

    /**
     * Get the value of company.
     */
    public function getCompany() : int
    {
        return $this->company;
    }

    /**
     * Set the value of company.
     *
     * @return  self
     */
    public function setCompany(int $company) : self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug.
     *
     * @return  self
     */
    public function setSlug(string $slug) : self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     *
     * @return  self
     */
    public function setCreatedAt(DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     *
     * @return  self
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt) : self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the value of deleted.
     */
    public function getDeleted() : int
    {
        return $this->deleted;
    }

    /**
     * Set the value of deleted.
     *
     * @return  self
     */
    public function setDeleted($deleted) : self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function delete(?string $field = null) : self
    {
        if (isset($this->$field)) {
            unset($this->$field);
        }
        return $this;
    }
}