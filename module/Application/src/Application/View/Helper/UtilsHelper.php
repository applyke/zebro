<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class UtilsHelper extends AbstractHelper
{
    /**
     * @var \Zend\View\HelperPluginManager
     */
    protected $pluginManager;

    public function __invoke()
    {
        return $this;
    }

    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    public function setPluginManager(\Zend\View\HelperPluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        return $this;
    }

    /**
     * Convert status[Int] to readable string
     *
     * @param $status
     * @return string
     */
    public function getStatus($status)
    {
        if ($status === 1) {
            return 'Active';
        }
        return 'Inactive';
    }

    /**
     * Check :id is present in route
     */
    public function isEdit()
    {
        $m = $this->getPluginManager();
        /** @var \Zend\ServiceManager\ServiceManager $sm */
        $sm = $m->getServiceLocator()->get('servicemanager');
        $application = $sm->get('application');
        $mvcEvent = $application->getMvcEvent();
        return (bool) $mvcEvent->getRouteMatch()->getParam('id');
    }

    /**
     * Get readbale action
     */
    public function getAction()
    {
        if ($this->isEdit()) {
            return 'Edit';
        } else {
            return 'Add';
        }
    }

    /**
     * @param int $status
     * @return string
     */
    public function getYesNo($status)
    {
        if ($status === 1) {
            return 'Yes';
        }
        return 'No';
    }

    public function formatMoney($val)
    {
        return number_format($val, 2, '.', ' ');
    }

    public function formatMoneyInput($val)
    {
        return number_format($val, 2, '.', '');
    }

    public function formatDateTime(\DateTime $date = null)
    {
        if (is_null($date)) {
            return '';
        }
        return $date->format('Y-m-d H:i:s');
    }

    public function trimText($s)
    {
        $stringLength = 100;
        $s = strip_tags($s);
        if (strlen($s) > $stringLength) {
            $pos = strpos($s, ' ', $stringLength);
            if ($pos === false) {
                return $s;
            }
            $s = mb_substr($s, 0, $pos);
            $s = rtrim($s, ',.!:');
            return $s . ' [...]';
        }
        return $s;
    }

    public function displayDescription($s)
    {
        $s = strip_tags($this->processText($s));
        $s = rtrim($s, ',.!:');
        return $s . '&nbsp;[...]';
    }

    public function displayMeta($s)
    {
        return htmlentities(strip_tags($s));
    }

    public function displayCreatedPreview(\DateTime $d)
    {
        $s = $d->format('M d, Y');
        $months = array(
            'Nov' => 'Nov',
            'Dec' => 'Dec',
            'Jan' => 'Jan',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Apr',
            'May' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Aug',
            'Sep' => 'Sep',
            'Oct' => 'Oct',
        );
        return str_replace(array_keys($months), array_values($months), $s);
    }

    public function processText($s)
    {
        return html_entity_decode($s);
    }

    public function escapeHtml($s)
    {
        $escaper = new \Zend\Escaper\Escaper('utf-8');
        return $escaper->escapeHtml($s);
    }

    public function url($url)
    {
        $url = ltrim($url, '/');
        return '/' . $url;
    }

    public function getImgSocialSrc($img, $maxWidth = 800)
    {
        $src = '';
        if ($maxWidth) {
            preg_match_all('/data-w(\d+)="(.*?)"/', $img, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $index => $width) {
                    if ($maxWidth <= (int) $width) {
                        break;
                    }
                }
                if (isset($matches[2][$index])) {
                    $src = $matches[2][$index];
                }
            }
        } else {
            // get max width
            preg_match_all('/data-w\d+="(.*?)"/', $img, $matches);
            $src = !empty($matches[1]) ? array_pop($matches[1]) : '';
        }
        if ($src) {
            /** @var \Closure $serverUrl */
            $serverUrl = $this->getPluginManager()->get('server_url');
            $src = $serverUrl($src);
        }
        return $src;
    }
}