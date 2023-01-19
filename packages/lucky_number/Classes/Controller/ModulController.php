<?php
namespace CodingBapthi\LuckyNumber\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

class ModuleController
{
    /**
     * @var \TYPO3\CMS\Lang\LanguageService
     */
    protected $languageService;

    /**
     * @var \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected $pageRenderer;

    /**
     * @var \TYPO3\CMS\Core\Database\ConnectionPool
     */
    protected $connectionPool;

    /**
     * @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected $backendUser;

    /**
     * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
     */
    protected $template;

    /**
     * @var \TYPO3\CMS\Backend\Routing\UriBuilder
     */
    protected $uriBuilder;

    public function __construct()
    {
        $this->languageService = $GLOBALS['LANG'];
        $this->pageRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $this->connectionPool = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class);
        $this->backendUser = $GLOBALS['BE_USER'];
        $this->template = $GLOBALS['TBE_TEMPLATE'];
        $this->uriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
    }

    public function mainAction()
    {
        $this->template->getDocumentTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/LuckyNumber/Main');
        $this->template->getDocumentTemplate()->getPageRenderer()->addInlineLanguageLabelFile('EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf');

        $this->template->getDocHeaderComponent()->setMetaInformation([]);

        $this->template->getDocHeaderComponent()->setButtonBar(
            $this->getButtonBar()
        );

        $this->template->getDocHeaderComponent()->setBackendUser($this->backendUser);

        $this->template->getDocHeaderComponent()->setModuleName('LuckyNumber');
        $this->template->getDocHeaderComponent()->setInlineJavaScript('
            function randomNumber() {
                var min = document.getElementById("min").value;
                var max = document.getElementById("max").value;
                var count = document.getElementById("count").value;
                document.getElementById("result").innerHTML = "";
                for (i = 0; i < count; i++) {
                    var random = Math.floor(Math.random() * (max - min + 1)) + min;
                    document.getElementById("result").innerHTML += random + "<br>";
                }
            }
        ');

        $content = '
        <form>
            <div class="form-group">
                <label for="min">'.$this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:min_value').'</label>
                <input type="number" class="form-control" id="min" required>
            </div>
            <div class="form-group">
                <label for="max">'.$this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:max_value').'</label>
                <input type="number" class="form-control" id="max" required>
            </div>
            <div class="form-group">
                <label for="count">'.$this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:count_value').'</label>
                <input type="number" class="form-control" id="count" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="randomNumber()">'.$this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:generate_button').'</button>
        </form>
        <div id="result"></div>';

        protected function getButtonBar(): ButtonBar
    {
        // create an instance of the button bar
        $buttonBar = GeneralUtility::makeInstance(ButtonBar::class);

        // create a save button
        $saveButton = $buttonBar->makeLinkButton()
            ->setHref('#')
            ->setTitle($this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:save_button'))
            ->setIcon($this->iconFactory->getIcon('actions-document-save', Icon::SIZE_SMALL))
            ->setShowLabelText(true);
        $buttonBar->addButton($saveButton, ButtonBar::BUTTON_POSITION_LEFT, 2);

        // create a cancel button
        $cancelButton = $buttonBar->makeLinkButton()
            ->setHref('#')
            ->setTitle($this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:cancel'))
            ->setLabel($this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:cancel'))
            ->setIcon($this->uriBuilder->getRelativeWebPath(
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('lucky_number', 'Resources/Public/Icons/cancel.svg')
            ))
            $buttonBar->addButton($cancelButton, ButtonBar::BUTTON_POSITION_LEFT, 1);


$content .= '<div class="form-group">
                <label for="count">'.$this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:count_value').'</label>
                <input type="number" class="form-control" id="count" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="randomNumber()">'.$this->languageService->sL('LLL:EXT:lucky_number/Resources/Private/Language/locallang_mod.xlf:generate_button').'</button>
        </form>
       

