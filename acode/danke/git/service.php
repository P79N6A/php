<?php

namespace App\Logics\Pangu\MAPI;

class Service
{

    public static function humanService()
    {
        return app(\App\ServiceApi\CSG\User\Service\UserServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\ARM\Contract\Customer\WithCustomerServiceIf
     */
    public static function withCustomerService()
    {
        return app(\App\ServiceApi\ARM\Contract\Customer\WithCustomerServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\Cleaning\CleaningServiceIf
     */
    public static function cleanTaskService()
    {
        return app(\App\ServiceApi\CRM\Task\Cleaning\CleaningServiceIf::class);
    }
    public static function repairTaskService()
    {
        return app(\App\ServiceApi\CRM\Task\Repair\RepairServiceIf::class);
    }
    public static function cleanTaskEntity()
    {
        return app(\App\ServiceApi\CRM\Task\Cleaning\Entity\Task::class);
    }

    /**
     * @return \App\ServiceApi\SCM\SuiteManage\Rooms\RoomServiceIf
     */
    public static function roomService()
    {
        return app(\App\ServiceApi\SCM\SuiteManage\Rooms\RoomServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\Cleaning\CleaningServiceIf
     */
    public static function cleaningService()
    {
        return app(\App\ServiceApi\CRM\Task\Cleaning\CleaningServiceIf::class);
    }
    public static function suitService()
    {
        return app(\App\ServiceApi\SCM\SuiteManage\Suites\SuiteServiceIf::class);
    }
    public static function feedBackService()
    {
        return app(\App\ServiceApi\CRM\Task\CustomerFeedback\CustomerFeedbackServiceIf::class);
    }
    public static function loginService()
    {
        return app(\App\ServiceApi\CSG\User\Service\LoginServiceIf::class);
    }
    public static function bankService()
    {
        return app(\App\ServiceApi\FMS\Bank\Service\BankServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\Resettlement\ResettlementServiceIf
     */
    public static function resettlementService()
    {
        return app(\App\ServiceApi\CRM\Task\Resettlement\ResettlementServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CSG\User\Service\VendorServiceIf
     */
    public static function venderService()
    {
        return app(\App\ServiceApi\CSG\User\Service\VendorServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CSG\Device\Lock\Service\LockServiceIf
     */
    public static function lockService()
    {
        return app(\App\ServiceApi\CSG\Device\Lock\Service\LockServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CSG\Location\Service\LocationServiceIf
     */
    public static function locationService()
    {
        return app(\App\ServiceApi\CSG\Location\Service\LocationServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\FE\App\Banner\Service\BannerServiceIf
     */
    public static function BannerService()
    {
        return app(\App\ServiceApi\FE\App\Banner\Service\BannerServiceIf::class);
    }

    /**

     * @return \App\ServiceApi\CRM\Task\ContractChange\CustomerContractChangeServiceIf|\Illuminate\Foundation\Application|mixed
     */
    public static function CustomerContractChangeService(){
        return app(\App\ServiceApi\CRM\Task\ContractChange\CustomerContractChangeServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CSG\User\Service\CorpUserServiceIf|\Illuminate\Foundation\Application|mixed
     */
    public static function CorpUserService(){
        return app(\App\ServiceApi\CSG\User\Service\CorpUserServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\CustomerProposal\CustomerProposalServiceIf|\Illuminate\Foundation\Application|mixed
     */
    public static function CustomerProposalService(){
        return app(\App\ServiceApi\CRM\Task\CustomerProposal\CustomerProposalServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\CustomerReview\Service\CustomerReviewServiceIf|\Illuminate\Foundation\Application|mixed
     */
    public static function CustomerReviewService(){
        return app(\App\ServiceApi\CRM\Task\CustomerReview\Service\CustomerReviewServiceIf::class);
    }
    /**
     * @return \App\ServiceApi\ARM\Booking\Service\BookingServiceIf
     */
    public static function BookingService()
    {
        return app(\App\ServiceApi\ARM\Booking\Service\BookingServiceIf::class);
    }

    /**
     * @return \App\ServiceCore\ARM\Contract\Landlord\Contract\WithLandlordService
     */
    public static function WithLandlordService()
    {
        return app(\App\ServiceCore\ARM\Contract\Landlord\Contract\WithLandlordService::class);
    }

    /**
     * @return \App\ServiceCore\ARM\Elecontract\EleContractService
     */
    public static function EleContractService()
    {
        return app(\App\ServiceCore\ARM\Elecontract\EleContractService::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\Steward\Service\StewardServiceIf
     */
    public static function StewardService()
    {
        return app(\App\ServiceApi\CRM\Task\Steward\Service\StewardServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\Telecom\TelecomServiceIf
     */
    public static function TelecomService()
    {
        return app(\App\ServiceApi\CRM\Task\Telecom\TelecomServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\CustomerReview\Service\CustomerReviewServiceIf|\Illuminate\Foundation\Application|mixed
     */
    public static function CustomerReviewServiceIf(){
        return app(\App\ServiceApi\CRM\Task\CustomerReview\Service\CustomerReviewServiceIf::class);
    }

    /**
     * @return \App\ServiceApi\CRM\Task\CustomerReview\Entity\CustomerServiceReviewRecord|\Illuminate\Foundation\Application|mixed
     */
    public static function CustomerServiceReviewRecord(){
        return app(\App\ServiceApi\CRM\Task\CustomerReview\Entity\CustomerServiceReviewRecord::class);
    }

    /**
     * @return \App\ServiceApi\SCM\SuiteManage\Suites\SuiteServiceIf|\Illuminate\Foundation\Application|mixed
     */
    public static function SuiteService()
    {
        return app(\App\ServiceApi\SCM\SuiteManage\Suites\SuiteServiceIf::class);
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public static function SignGiftService()
    {
        return app(\App\ServiceApi\FE\Activity\SignGift\Service\SignGiftIf::class);
    }
}
