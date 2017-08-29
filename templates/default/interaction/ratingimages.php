<?php
$ratingValue = $rating['Rating'];
$i0  = '<img src="/templates/default/images/rate0.png" width="25" height="24" alt="">';
$i14 = '<img src="/templates/default/images/rate14.png" width="25" height="24" alt="">';
$i12 = '<img src="/templates/default/images/rate12.png" width="25" height="24" alt="">';
$i34 = '<img src="/templates/default/images/rate34.png" width="25" height="24" alt="">';
$i1  = '<img src="/templates/default/images/rate1.png" width="25" height="24" alt="">';
if ($ratingValue < 0.25) {
    echo $i0 . $i0 . $i0 . $i0 . $i0;
} else if ($ratingValue < 0.5) {
    echo $i14 . $i0 . $i0 . $i0 . $i0;
} else if ($ratingValue < 0.75) {
    echo $i12 . $i0 . $i0 . $i0 . $i0;
} else if ($ratingValue < 1) {
    echo $i34 . $i0 . $i0 . $i0 . $i0;
} else if ($ratingValue < 1.25) {
    echo $i1 . $i0 . $i0 . $i0 . $i0;
} else if ($ratingValue < 1.5) {
    echo $i1 . $i14 . $i0 . $i0 . $i0;
} else if ($ratingValue < 1.75) {
    echo $i1 . $i12 . $i0 . $i0 . $i0;
} else if ($ratingValue < 2) {
    echo $i1 . $i34 . $i0 . $i0 . $i0;
} else if ($ratingValue < 2.25) {
    echo $i1 . $i1 . $i0 . $i0 . $i0;
} else if ($ratingValue < 2.5) {
    echo $i1 . $i1 . $i14 . $i0 . $i0;
} else if ($ratingValue < 2.75) {
    echo $i1 . $i1 . $i12 . $i0 . $i0;
} else if ($ratingValue < 3) {
    echo $i1 . $i1 . $i34 . $i0 . $i0;
} else if ($ratingValue < 3.25) {
    echo $i1 . $i1 . $i1 . $i0 . $i0;
} else if ($ratingValue < 3.5) {
    echo $i1 . $i1 . $i1 . $i14 . $i0;
} else if ($ratingValue < 3.75) {
    echo $i1 . $i1 . $i1 . $i12 . $i0;
} else if ($ratingValue < 4) {
    echo $i1 . $i1 . $i1 . $i34 . $i0;
} else if ($ratingValue < 4.25) {
    echo $i1 . $i1 . $i1 . $i1 . $i0;
} else if ($ratingValue < 4.5) {
    echo $i1 . $i1 . $i1 . $i1 . $i14;
} else if ($ratingValue < 4.75) {
    echo $i1 . $i1 . $i1 . $i1 . $i12;
} else if ($ratingValue < 5) {
    echo $i1 . $i1 . $i1 . $i1 . $i34;
} else {
    echo $i1 . $i1 . $i1 . $i1 . $i1;
}
