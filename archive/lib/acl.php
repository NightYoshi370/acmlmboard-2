<?php

/* Load Access Control List for user $uid. 0 yields the default list. */
function load_acl($uid) {
  global $sql,$default_acl;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  
  $acl=$default_acl;
  $q=$sql->query("SELECT tr.r FROM usertokens ut JOIN tokenrights tr ON tr.t=ut.t WHERE ut.u='$uid'");
  while($d=$sql->fetch($q)) {
    $acl[$d[r]]=1;
  }

  return $acl;
}

/* Render a presentation of a token, if available. */
function present_token($tid) {
  global $tokens;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");

  if($tokens[$tid][img]=="") return "";
  
  return "<img src='".$tokens[$tid][img]."' title='".htmlentities($tokens[$tid][name])."' style='vertical-align:text-bottom;padding-right:2px'>";
}

/* Return a presentation of all tokens for a user. */
function gettokenstring($uid) {
  global $sql;
  static $cache;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  
  if(isset($cache[$uid])) return $cache[$uid];
  else {
    $q=$sql->query("SELECT t FROM usertokens WHERE u='$uid'");
    $str="";
    while($d=$sql->fetch($q)) $str.=present_token($d[t]);
    if($str!="") $str.="<br>";
    return ($cache[$uid]=$str);
  }
}

/* specific ACL resolution functions */
function acl_for_thread($tid,$key) {
  global $loguser;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if($loguser[acl]["$key t$tid"]) return 1;
  else if($loguser[acl]["not $key t$tid"]) return 0;
  else if($loguser[acl]["$key f".getforumbythread($tid)]) return 1;
  else if($loguser[acl]["not $key f".getforumbythread($tid)]) return 0;
  else if($loguser[acl]["$key c".getcategorybythread($tid)]) return 1;
  else if($loguser[acl]["not $key c".getcategorybythread($tid)]) return 0;
  else if($loguser[acl][$key]) return 1;
  else if($loguser[acl]["not $key"]) return 0;
  return 0;
}

function acl_for_forum($fid,$key) {
  global $loguser;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if($loguser[acl]["$key f$fid"]) return 1;
  else if($loguser[acl]["not $key f$fid"]) return 0;
  else if($loguser[acl]["$key c".getcategorybyforum($fid)]) return 1;
  else if($loguser[acl]["not $key c".getcategorybyforum($fid)]) return 0;
  else if($loguser[acl][$key]) return 1;
  else if($loguser[acl]["not $key"]) return 0;
  return 0;
}

function acl_for_user($uid,$key) {
  global $loguser;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if($loguser[acl]["$key u$uid"]) return 1;
  else if($loguser[acl]["not $key u$uid"]) return 0;
  else if($loguser[acl][$key]) return 1;
  else if($loguser[acl]["not $key"]) return 0;
  return 0;
}

function acl($key) {
  global $loguser;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if($loguser[acl][$key]) return 1;
  else if($loguser[acl]["not $key"]) return 0;
  return 0;
}

function acl_or_die($key) {
  global $L;
  global $sql;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if (acl($key)) {
	return true;
  }
  else {
    $r = $sql->fetchp('SELECT title FROM rights WHERE r=?',array($key));
    $name = $r['title'];
    print "$L[TBL1]>
".        "  $L[TD1c]>
".        "    You do not have the permissions to $name.<br>
".        "    <a href=./>Back to main</a>
".        "$L[TBLend]
";
    die();
  }
}

/* Lists for replacement of minpower conditions - make more efficient e.g. by filling forum->cat caches */
function forums_with_right($key) {
  global $loguser,$sql;
  static $cache;
  die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if($cache) return $cache;
  $cache="(";
  $r=$sql->query("SELECT id FROM forums");
  while($d=$sql->fetch($r)) {
    if(acl_for_forum($d[id],$key)) $cache.="$d[id],";
  }
  $cache.="NULL)";
  return $cache;
}

/* Legacy */
// those are still being used apparently, namely by shop.php... -- Mega-Mario
function isadmin(){
  global $loguser;
  //die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  return $loguser[power]>=3;
}

function ismod($fid=0){
  global $loguser;
  //die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  if($loguser[power]==1) return isset($loguser[modforums][$fid]);
  return $loguser[power]>=2;
}

function isbanned(){
  global $loguser;
  //die((__FUNCTION__)." is legacy and shouldn't be used. Someone fix that.");
  return $loguser[power]<0;
}
/* End Legacy */


?>