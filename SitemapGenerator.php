<?php

/**
*	Sitemap Generator
*	@tihoho, Silent Labs, 2017.
*/

namespace Silentlabs;

class SitemapGenerator {
	
	public 
		$priorityDefault, $changefreqDefault, $lastmodDefault,
		$withPriority, $withChangefreq, $withLastmod,
		$domain, $links, $generated, $isSaved;
		
	/**
	*	Construct
	*	@param string|null Domain name
	*	@return void
	*/
	public function __construct($domain = null) {
		
		$this->domain = $domain ? $domain : 'https://example.com/';
		$this->lastmodDefault = date('Y-m-d');
		$this->changefreqDefault = 'daily';
		$this->priorityDefault = '1.0';
		$this->generated = false;
		$this->isSaved = false;
		$this->links = [];
	
	}
	
	/**
	*	Set a domain
	*	@param string Domain name
	*	@return <SitemapGenerator>
	*/
	public function setDomain($domain) {
		
		$domain = rtrim($domain, '/');
		$this->domain = $domain;
		$this->addLink('');
		
		return $this;
	}
	
	/**
	*	Ignore a lastmod tag in url
	*	@return <SitemapGenerator>
	*/
	public function ignoreLastmod() {
		$this->withLastmod = false;
		return $this;
	}
	
	/**
	*	Ignore a priority tag in url
	*	@return <SitemapGenerator>
	*/
	public function ignorePriority() {
		$this->withPriority = false;
		return $this;
	}
	
	/**
	*	Ignore a changefreq tag in url
	*	@return <SitemapGenerator>
	*/
	public function ignoreChangefreq() {
		$this->withChangefreq = false;
		return $this;
	}
	
	/**
	*	Set link priority
	*	@param string|float $priority between 0.1 and 1.0
	*	@return <SitemapGenerator>
	*/
	public function setPriority($priority) {
		$this->priorityDefault = $priority;
		return $this;
	}
	
	/**
	*	Set change frequency of a link
	*	@param string $changefreq frequency
	*	@return <SitemapGenerator>
	*/
	public function setChangefreq($changefreq) {
		$this->changefreqDefault =
			in_array($changefreq, ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])
			? $changefreq
			: 'daily';
			
		return $this;
	}
	
	/**
	*	Set last modified date of a link
	*	@param string $lastmod last modified
	*	@return <SitemapGenerator>
	*/
	public function setLastmod($lastmod) {
		$this->lastmodDefault = $lastmod;
		return $this;
	}
	
	/**
	*	Set defaults values for changefreq, lastmod and priority tags
	*	@param array $params New values as associate array
	*	@example $this->setDefaults(['lastmod' => '2017-01-01', 'priority' => '0.8']);
	*	@return <SitemapGenerator>
	*/
	public function setDefaults($params = []) {
		foreach($params as $k => $v) {
			if(in_array($k, ['changefreq', 'lastmod', 'priority'])) {
				$this->{$k.'Default'} = $v;
			}
		}
		return $this;
	}
	
	/**
	*	Add link to Sitemap
	*	@param string $loc URL
	*	@param string|null $priority Priority
	*	@param string|null $changefreq Change frequency
	*	@param string|null $lastmod Last Modified
	*	@return <SitemapGenerator>
	*/
	public function addLink($loc, $priority = null, $changefreq = null, $lastmod = null) {
		
		$loc = trim($loc, '/ ');
		$newlink = [];
		$newlink['loc'] = $loc;
		
		if($this->withPriority)
			$newlink['priority'] = $priority !== null ? $priority : $this->priorityDefault; 
		
		if($this->withChangefreq)
			$newlink['changefreq'] = $changefreq !== null ? $changefreq : $this->changefreqDefault;
		
		if($this->withLastmod)
			$newlink['lastmod'] = $lastmod !== null ? $lastmod : $this->lastmodDefault; 
		
		
		$this->links[] = $newlink;
		
		return $this;
		
	}
	
	/**
	*	Build a Sitemap file
	*	
	*	@return <SitemapGenerator>
	*/
	public function build() {
		
		if(count($this->links)) {
			
			$sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
			$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
			foreach($this->links as $link) {
				$loc = '<loc>' . $this->domain . '/' . $link['loc'] . '</loc>';
				$priority = isset($link['priority']) ? '<priority>' . $link['priority'] . '</priority>' : '';
				$changefreq = isset($link['changefreq']) ? '<changefreq>' . $link['changefreq'] . '</changefreq>' : '';
				$lastmod = isset($link['lastmod']) ? '<lastmod>' . $link['lastmod'] . '</lastmod>' : '';
				$sitemap .= "<url>$loc$priority$changefreq$lastmod</url>";
			}
			
			$sitemap .= '</urlset>';
			
			$this->generated = $sitemap;
			
		}
		
		return $this;
		
	}
	
	/**
	*	Get content of a Sitemap generated file
	*	
	*	@return string Sitemap structure
	*/
	public function get() {
		
		if($this->generated)
			return $this->generated;
		else
			return 'Map not builded yet.';
	}

	/**
	*	Save Sitemap on local machine
	*	@param string $path Path to save
	*	@return bool Successfully or aborted save
	*/
	public function save($path) {
		
		if(!$this->generated)
			return false;
		
		$this->isSaved = (bool) file_put_contents($path, $this->generated);
		
		return $this->isSaved;
		
	}
	
}
