<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\payment;
use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;

/**
 * Class checkoutWithBankTransferDiscount
 * @package Frontend\payment
 * @since 2.1.2
 */
class checkoutWithBankTransferDiscount extends CheckoutMissingData
{
	/**
	 * @param $productName
	 * @param $categoryName
	 * @param $custome@since 2.1.2rInformation
	 * @throws \Exception
	 *
	 */
	public function checkoutProductWithBankTransferDiscountPayment($productName, $categoryName, $customerInformation)
	{
		$I = $this;
		$I->addToCart($categoryName, $productName);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->amOnPage(FrontEndProductManagerJoomla3Page:: $cartPageUrL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$labelPayment, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$labelPayment);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount, 30);
		$I->checkOption(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount);
		$I->wait(0.5);

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$paymentBankTransferDiscount);
		}

		$I->waitForElementVisible($productFrontEndManagerPage->product($productName), 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS($productFrontEndManagerPage->radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);
		
		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->wait(0.5);

		try
		{
			$I->waitForElementNotVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 10);
		}catch (\Exception $e)
		{
			$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
			$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		}

		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}