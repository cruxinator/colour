<?php
namespace cruxinator\Colours;

use \Exception;

/**
 * A colour utility that helps manipulate HEX colours
 */
class Colour
{

    /**
     * Auto darkens/lightens by 10% for sexily-subtle gradients.
     * Set this to FALSE to adjust automatic shade to be between given colour
     * and black (for darken) or white (for lighten)
     */
    const DEFAULT_ADJUST = 10;

    private $_hex;
    private $_hsl;
    private $_rgb;

    /**
     * Instantiates the class with a HEX value
     * @param string $hex
     * @throws Exception "Bad colour format"
     */
    public function __construct($hex)
    {
        // Strip # sign is present
        $colour = str_replace("#", "", $hex);

        // Make sure it's 6 digits
        if (strlen($colour) === 3) {
            $colour = $colour[0] . $colour[0] . $colour[1] . $colour[1] . $colour[2] . $colour[2];
        } elseif (strlen($colour) != 6) {
            throw new Exception("HEX colour needs to be 6 or 3 digits long");
        }

        $this->_hsl = self::hexToHsl($colour);
        $this->_hex = $colour;
        $this->_rgb = self::hexToRgb($colour);
    }
    
    /**
     * Converts object into its string representation
     * @return string Colour
     */
    public function __toString()
    {
        return "#" . $this->getHex();
    }

    public function __get($name)
    {
        switch (strtolower($name)) {
            case 'red':
            case 'r':
                return $this->_rgb["R"];
            case 'green':
            case 'g':
                return $this->_rgb["G"];
            case 'blue':
            case 'b':
                return $this->_rgb["B"];
            case 'hue':
            case 'h':
                return $this->_hsl["H"];
            case 'saturation':
            case 's':
                return $this->_hsl["S"];
            case 'lightness':
            case 'l':
                return $this->_hsl["L"];
        }
    }

    public function __set($name, $value)
    {
        switch (strtolower($name)) {
            case 'red':
            case 'r':
                $this->_rgb["R"] = $value;
                $this->_hex = $this->rgbToHex($this->_rgb);
                $this->_hsl = $this->hexToHsl($this->_hex);
                break;
            case 'green':
            case 'g':
                $this->_rgb["G"] = $value;
                $this->_hex = $this->rgbToHex($this->_rgb);
                $this->_hsl = $this->hexToHsl($this->_hex);
                break;
            case 'blue':
            case 'b':
                $this->_rgb["B"] = $value;
                $this->_hex = $this->rgbToHex($this->_rgb);
                $this->_hsl = $this->hexToHsl($this->_hex);
                break;
            case 'hue':
            case 'h':
                $this->_hsl["H"] = $value;
                $this->_hex = $this->hslToHex($this->_hsl);
                $this->_rgb = $this->hexToRgb($this->_hex);
                break;
            case 'saturation':
            case 's':
                $this->_hsl["S"] = $value;
                $this->_hex = $this->hslToHex($this->_hsl);
                $this->_rgb = $this->hexToRgb($this->_hex);
                break;
            case 'lightness':
            case 'light':
            case 'l':
                $this->_hsl["L"] = $value;
                $this->_hex = $this->hslToHex($this->_hsl);
                $this->_rgb = $this->hexToRgb($this->_hex);
                break;
        }
    }

    // ====================
    // = Public Interface =
    // ====================

    /**
     * Given a HEX string returns a HSL array equivalent.
     * @param string $colour
     * @return array HSL associative array
     */
    public static function hexToHsl($color)
    {

        // Sanity check
        $color = self::_checkHex($color);

        // Convert HEX to DEC
        $R = hexdec($color[0] . $color[1]);
        $G = hexdec($color[2] . $color[3]);
        $B = hexdec($color[4] . $color[5]);

        $HSL = array();

        $var_R = ($R / 255);
        $var_G = ($G / 255);
        $var_B = ($B / 255);

        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        $del_Max = $var_Max - $var_Min;

        $L = ($var_Max + $var_Min) / 2;

        if ($del_Max == 0) {
            $H = 0;
            $S = 0;
        } else {
            if ($L < 0.5) {
                $S = $del_Max / ($var_Max + $var_Min);
            } else {
                $S = $del_Max / (2 - $var_Max - $var_Min);
            }

            $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
            $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
            $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

            if ($var_R == $var_Max) {
                $H = $del_B - $del_G;
            } elseif ($var_G == $var_Max) {
                $H = (1 / 3) + $del_R - $del_B;
            } elseif ($var_B == $var_Max) {
                $H = (2 / 3) + $del_G - $del_R;
            } else {
                $H = 0;
            }
            

            if ($H < 0) {
                $H++;
            }
            if ($H > 1) {
                $H--;
            }
        }

        $HSL['H'] = ($H * 360);
        $HSL['S'] = $S;
        $HSL['L'] = $L;

        return $HSL;
    }
    /**
     *  Given a HSL associative array returns the equivalent HEX string
     * @param array $hsl
     * @return string HEX string
     * @throws Exception "Bad HSL Array"
     */
    public static function hslToHex($hsl = array())
    {
        // Make sure it's HSL
        if (empty($hsl) || !isset($hsl["H"]) || !isset($hsl["S"]) || !isset($hsl["L"])) {
            throw new Exception("Param was not an HSL array");
        }

        list($H, $S, $L) = array($hsl['H'] / 360, $hsl['S'], $hsl['L']);

        if ($S == 0) {
            $r = (int) $L * 255;
            $g = (int) $L * 255;
            $b = (int) $L * 255;
        } else {
            if ($L < 0.5) {
                $var_2 = $L * (1 + $S);
            } else {
                $var_2 = ($L + $S) - ($S * $L);
            }

            $var_1 = 2 * $L - $var_2;

            $r = (int) round(255 * self::_huetorgb($var_1, $var_2, $H + (1 / 3)));
            $g = (int) round(255 * self::_huetorgb($var_1, $var_2, $H));
            $b = (int) round(255 * self::_huetorgb($var_1, $var_2, $H - (1 / 3)));
        }

        // Convert to hex
        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);

        // Make sure we get 2 digits for decimals
        $r = (strlen("" . $r) === 1) ? "0" . $r : $r;
        $g = (strlen("" . $g) === 1) ? "0" . $g : $g;
        $b = (strlen("" . $b) === 1) ? "0" . $b : $b;

        return $r . $g . $b;
    }


    public static function hsvToHex(array $hsv)
    {
        list($H, $S, $V) = $hsv;
        //1
        $H *= 6;
        //2
        $I = floor($H);
        $F = $H - $I;
        //3
        $M = $V * (1 - $S);
        $N = $V * (1 - $S * $F);
        $K = $V * (1 - $S * (1 - $F));
        //4
        switch ($I) {
            case 0:
                list($R, $G, $B) = array($V, $K, $M);
                break;
            case 1:
                list($R, $G, $B) = array($N, $V, $M);
                break;
            case 2:
                list($R, $G, $B) = array($M, $V, $K);
                break;
            case 3:
                list($R, $G, $B) = array($M, $N, $V);
                break;
            case 4:
                list($R, $G, $B) = array($K, $M, $V);
                break;
            case 5:
            case 6: //for when $H=1 is given
                list($R, $G, $B) = array($V, $M, $N);
                break;
    }
        $hex[0] = str_pad(dechex($R), 2, '0', STR_PAD_LEFT);
        $hex[1] = str_pad(dechex($G), 2, '0', STR_PAD_LEFT);
        $hex[2] = str_pad(dechex($B), 2, '0', STR_PAD_LEFT);
        return implode('', $hex);
    }


    /**
     * Given a HEX string returns a RGB array equivalent.
     * @param string $colour
     * @return array RGB associative array
     */
    public static function hexToRgb($colour)
    {

        // Sanity check
        $colour = self::_checkHex($colour);

        // Convert HEX to DEC
        $R = hexdec($colour[0] . $colour[1]);
        $G = hexdec($colour[2] . $colour[3]);
        $B = hexdec($colour[4] . $colour[5]);

        $RGB['R'] = $R;
        $RGB['G'] = $G;
        $RGB['B'] = $B;

        return $RGB;
    }


    /**
     *  Given an RGB associative array returns the equivalent HEX string
     * @param array $rgb
     * @return string RGB string
     * @throws Exception "Bad RGB Array"
     */
    public static function rgbToHex($rgb = array())
    {
        // Make sure it's RGB
        if (empty($rgb) || !isset($rgb["R"]) || !isset($rgb["G"]) || !isset($rgb["B"])) {
            throw new Exception("Param was not an RGB array");
        }

        // Convert RGB to HEX
        $hex[0] = str_pad(dechex($rgb['R']), 2, '0', STR_PAD_LEFT);
        $hex[1] = str_pad(dechex($rgb['G']), 2, '0', STR_PAD_LEFT);
        $hex[2] = str_pad(dechex($rgb['B']), 2, '0', STR_PAD_LEFT);

        return implode('', $hex);
    }


    /**
     * Given a HEX value, returns a darker colour. If no desired amount provided, then the colour halfway between
     * given HEX and black will be returned.
     * @param int $amount
     * @return string Darker HEX value
     */
    public function darken($amount = self::DEFAULT_ADJUST)
    {
        // Darken
        $darkerHSL = $this->_darken($this->_hsl, $amount);
        // Return as HEX
        return self::hslToHex($darkerHSL);
    }

    /**
     * Given a HEX value, returns a lighter colour. If no desired amount provided, then the colour halfway between
     * given HEX and white will be returned.
     * @param int $amount
     * @return string Lighter HEX value
     */
    public function lighten($amount = self::DEFAULT_ADJUST)
    {
        // Lighten
        $lighterHSL = $this->_lighten($this->_hsl, $amount);
        // Return as HEX
        return self::hslToHex($lighterHSL);
    }

    /**
     * Given a HEX value, returns a mixed colour. If no desired amount provided, then the colour mixed by this ratio
     * @param string $hex2 Secondary HEX value to mix with
     * @param int $amount = -100..0..+100
     * @return string mixed HEX value
     */
    public function mix($hex2, $amount = 0)
    {
        $rgb2 = self::hexToRgb($hex2);
        $mixed = $this->_mix($this->_rgb, $rgb2, $amount);
        // Return as HEX
        return self::rgbToHex($mixed);
    }

    /**
     * Creates an array with two shades that can be used to make a gradient
     * @param int $amount Optional percentage amount you want your contrast colour
     * @return array An array with a 'light' and 'dark' index
     */
    public function makeGradient($amount = self::DEFAULT_ADJUST)
    {
        // Decide which colour needs to be made
        if ($this->isLight()) {
            $lightColour = $this->_hex;
            $darkColour = $this->darken($amount);
        } else {
            $lightColour = $this->lighten($amount);
            $darkColour = $this->_hex;
        }

        // Return our gradient array
        return array("light" => $lightColour, "dark" => $darkColour);
    }


    /**
     * Returns whether or not given colour is considered "light"
     * @param string|Boolean $colour
     * @param int $lighterThan
     * @return boolean
     */
    public function isLight($colour = false, $lighterThan = 130)
    {
        // Get our colour
        $colour = ($colour) ? $colour : $this->_hex;

        // Calculate straight from rbg
        $r = hexdec($colour[0] . $colour[1]);
        $g = hexdec($colour[2] . $colour[3]);
        $b = hexdec($colour[4] . $colour[5]);

        return (($r * 299 + $g * 587 + $b * 114) / 1000 > $lighterThan);
    }

    /**
     * Returns whether or not a given colour is considered "dark"
     * @param string|Boolean $colour
     * @param int $darkerThan
     * @return boolean
     */
    public function isDark($colour = false, $darkerThan = 130)
    {
        // Get our colour
        $colour = ($colour) ? $colour : $this->_hex;

        // Calculate straight from rbg
        $r = hexdec($colour[0] . $colour[1]);
        $g = hexdec($colour[2] . $colour[3]);
        $b = hexdec($colour[4] . $colour[5]);

        return (($r * 299 + $g * 587 + $b * 114) / 1000 <= $darkerThan);
    }

    /**
     * Returns the complimentary colour
     * @return string Complementary hex colour
     *
     */
    public function complementary()
    {
        // Get our HSL
        $hsl = $this->_hsl;

        // Adjust Hue 180 degrees
        $hsl['H'] += ($hsl['H'] > 180) ? -180 : 180;

        // Return the new value in HEX
        return self::hslToHex($hsl);
    }
    
    /**
     * Returns your colour's HSL array
     */
    public function getHsl()
    {
        return $this->_hsl;
    }
    /**
     * Returns your original colour
     */
    public function getHex()
    {
        return $this->_hex;
    }
    /**
     * Returns your colour's RGB array
     */
    public function getRgb()
    {
        return $this->_rgb;
    }
    
    /**
     * Returns the cross browser CSS3 gradient
     * @param int $amount Optional: percentage amount to light/darken the gradient
     * @param boolean $vintageBrowsers Optional: include vendor prefixes for browsers that almost died out already
     * @param string $prefix Optional: prefix for every lines
     * @param string $suffix Optional: suffix for every lines
     * @link  http://caniuse.com/css-gradients Resource for the browser support
     * @return string CSS3 gradient for chrome, safari, firefox, opera and IE10
     */
    public function getCssGradient($amount = self::DEFAULT_ADJUST, $vintageBrowsers = false, $suffix = "", $prefix = "")
    {

        // Get the recommended gradient
        $g = $this->makeGradient($amount);

        $css = "";
        /* fallback/image non-cover colour */
        $css .= "{$prefix}background-color: #" . $this->_hex . ";{$suffix}";

        /* IE Browsers */
        $css .= "{$prefix}filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#" . $g['light'] . "', endColorstr='#" . $g['dark'] . "');{$suffix}";

        /* Safari 4+, Chrome 1-9 */
        if ($vintageBrowsers) {
            $css .= "{$prefix}background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#" . $g['light'] . "), to(#" . $g['dark'] . "));{$suffix}";
        }

        /* Safari 5.1+, Mobile Safari, Chrome 10+ */
        $css .= "{$prefix}background-image: -webkit-linear-gradient(top, #" . $g['light'] . ", #" . $g['dark'] . ");{$suffix}";

        /* Firefox 3.6+ */
        if ($vintageBrowsers) {
            $css .= "{$prefix}background-image: -moz-linear-gradient(top, #" . $g['light'] . ", #" . $g['dark'] . ");{$suffix}";
        }

        /* Opera 11.10+ */
        if ($vintageBrowsers) {
            $css .= "{$prefix}background-image: -o-linear-gradient(top, #" . $g['light'] . ", #" . $g['dark'] . ");{$suffix}";
        }

        /* Unprefixed version (standards): FF 16+, IE10+, Chrome 26+, Safari 7+, Opera 12.1+ */
        $css .= "{$prefix}background-image: linear-gradient(to bottom, #" . $g['light'] . ", #" . $g['dark'] . ");{$suffix}";

        // Return our CSS
        return $css;
    }

    // ===========================
    // = Private Functions Below =
    // ===========================


    /**
     * Darkens a given HSL array
     * @param array $hsl
     * @param int $amount
     * @return array $hsl
     */
    private function _darken($hsl, $amount = self::DEFAULT_ADJUST)
    {
        // Check if we were provided a number
        if ($amount) {
            $hsl['L'] = ($hsl['L'] * 100) - $amount;
            $hsl['L'] = ($hsl['L'] < 0) ? 0 : $hsl['L'] / 100;
        } else {
            // We need to find out how much to darken
            $hsl['L'] = $hsl['L'] / 2;
        }

        return $hsl;
    }

    /**
     * Lightens a given HSL array
     * @param array $hsl
     * @param int $amount
     * @return array $hsl
     */
    private function _lighten($hsl, $amount = self::DEFAULT_ADJUST)
    {
        // Check if we were provided a number
        if ($amount) {
            $hsl['L'] = ($hsl['L'] * 100) + $amount;
            $hsl['L'] = ($hsl['L'] > 100) ? 1 : $hsl['L'] / 100;
        } else {
            // We need to find out how much to lighten
            $hsl['L'] += (1 - $hsl['L']) / 2;
        }

        return $hsl;
    }

    /**
     * Mix 2 rgb colours and return an rgb colour
     * @param array $rgb1
     * @param array $rgb2
     * @param int $amount ranged -100..0..+100
     * @return array $rgb
     *
     * 	ported from http://phpxref.pagelines.com/nav.html?includes/class.colors.php.source.html
     */
    private function _mix($rgb1, $rgb2, $amount = 0)
    {
        $r1 = ($amount + 100) / 100;
        $r2 = 2 - $r1;

        $rmix = (($rgb1['R'] * $r1) + ($rgb2['R'] * $r2)) / 2;
        $gmix = (($rgb1['G'] * $r1) + ($rgb2['G'] * $r2)) / 2;
        $bmix = (($rgb1['B'] * $r1) + ($rgb2['B'] * $r2)) / 2;

        return array('R' => $rmix, 'G' => $gmix, 'B' => $bmix);
    }

    /**
     * Given a Hue, returns corresponding RGB value
     * @param int $v1
     * @param int $v2
     * @param int $vH
     * @return int
     */
    private static function _huetorgb($v1, $v2, $vH)
    {
        if ($vH < 0) {
            $vH += 1;
        }

        if ($vH > 1) {
            $vH -= 1;
        }

        if ((6 * $vH) < 1) {
            return ($v1 + ($v2 - $v1) * 6 * $vH);
        }

        if ((2 * $vH) < 1) {
            return $v2;
        }

        if ((3 * $vH) < 2) {
            return ($v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6);
        }

        return $v1;
    }

    /**
     * You need to check if you were given a good hex string
     * @param string $hex
     * @return string Colour
     * @throws Exception "Bad colour format"
     */
    private static function _checkHex($hex)
    {
        // Strip # sign is present
        $colour = str_replace("#", "", $hex);

        // Make sure it's 6 digits
        if (strlen($colour) == 3) {
            $colour = $colour[0] . $colour[0] . $colour[1] . $colour[1] . $colour[2] . $colour[2];
        } elseif (strlen($colour) != 6) {
            throw new Exception("HEX colour needs to be 6 or 3 digits long");
        }

        return $colour;
    }
}
