<?php
/**
 * ownCloud
 *
 * @author Dipak Acharya <dipak@jankaritech.com>
 * @copyright Copyright (c) 2018 Dipak Acharya dipak@jankaritech.com
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License,
 * as published by the Free Software Foundation;
 * either version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace Page;

use Behat\Mink\Session;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

/**
 * Tags page.
 */
class TagsPage extends FilesPageBasic {
	protected $path = '/index.php/apps/files/?view=systemtagsfilter';
	protected $fileNamesXpath = "//div[@id='app-content-systemtagsfilter']//span[contains(@class,'nametext') and not(contains(@class,'innernametext'))]";
	protected $fileNameMatchXpath = "//span[contains(@class,'nametext') and not(contains(@class,'innernametext')) and .=%s]";
	protected $fileListXpath = ".//div[@id='app-content-systemtagsfilter']//tbody[@id='fileList']";
	protected $emptyContentXpath = ".//div[@id='app-content-systemtagsfilter']//div[@id='emptycontent']";
	protected $filePathInRowXpath = ".//div[@id='app-content-systemtagsfilter']//tbody[@id='fileList']//tr";

	private $tagsInputXpath = "//div[@id='app-content-systemtagsfilter']//li[@class='select2-search-field']//input";
	private $tagsSuggestDropDown = "//div[contains(@class, 'select2-drop-active') and contains(@id, 'select2-drop')]";

	/**
	 * @return string
	 */
	protected function getFileListXpath() {
		return $this->fileListXpath;
	}

	/**
	 * @return string
	 */
	protected function getFileNamesXpath() {
		return $this->fileNamesXpath;
	}

	/**
	 * @return string
	 */
	protected function getFileNameMatchXpath() {
		return $this->fileNameMatchXpath;
	}

	/**
	 * @return string
	 */
	protected function getEmptyContentXpath() {
		return $this->emptyContentXpath;
	}

	/**
	 * @return void
	 * @throws ElementNotFoundException
	 */
	protected function getFilePathInRowXpath() {
		throw new \Exception("not implemented in TagsPage");
	}

	/**
	 * Search the given tag in the tags page
	 *
	 * @param string $tagName
	 *
	 * @return void
	 * @throws ElementNotFoundException
	 */
	public function searchByTag($tagName) {
		$this->waitTillElementIsNotNull($this->tagsInputXpath);
		$inputField = $this->find("xpath", $this->tagsInputXpath);

		/**
		 * @return string
		 */
		if ($inputField === null) {
			throw new ElementNotFoundException(
				__METHOD__ .
				" xpath $this->tagsInputXpath " .
				"could not find input field"
			);
		}
		$inputField->focus();
		$inputField->setValue($tagName);
		$this->waitTillElementIsNotNull($this->getTagsDropDownResultsXpath());
		$tagSuggestions = $this->findAll("xpath", $this->getTagsDropDownResultsXpath());
		foreach ($tagSuggestions as $tag) {
			if ($tag->getText() === $tagName) {
				$tag->click();
			}
		}
	}

	/**
	 * Returns xpath of the tag results dropdown elements
	 *
	 * @return string
	 */
	public function getTagsDropDownResultsXpath() {
		$resultXpath = $this->tagsSuggestDropDown .
			"//ul[@class='select2-results']" .
			"//span";
		return $resultXpath;
	}
}
