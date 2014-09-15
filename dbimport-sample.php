<?php
define('DB_PASS', 'dbname=DBNAME user=username password=password');

echo <<<HTML
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="author" content="sacrifs">
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" href="../css/iidxsd.css" />
	<title>IIDXSD Data Importer</title>
</head>
<body>
<form method="POST">
<input type="text" id="url" name="url" value="http://example.com/data2iidxsd.sql" size="50" /><br />
<input type="submit" value="import" />
</form>
<form method="POST">
<input type="hidden" id="split" name="split" value="1" />
<input type="submit" value="split" />
</form>
</body>
</html>
HTML;

if($_POST["split"] == "1"){
	execSplit();
}
if($_POST["url"]){
	inputData();
}


function inputData(){
	$url = $_POST["url"];
	$data = file_get_contents($url);
	$data = str_replace("\r\n", "\n", $data);
	$data = str_replace("\r", "\n", $data);
	$list = explode("\n", $data);
	array_shift($list);
	//$num = count($list);
	$sql = <<<SQL
DROP TABLE IF EXISTS temp cascade;
CREATE TABLE temp(
id serial,
no character(4) not null,
ver smallint,
msc text,
spn smallint,
sph smallint,
spa smallint,
spd smallint,
dpn smallint,
dph smallint,
dpa smallint,
dpd smallint,
n7 integer,
h7 integer,
a7 integer,
d7 integer,
n14 integer,
h14 integer,
a14 integer,
d14 integer,
bpm integer,
bpmn integer,
bpmx integer,
bpmd text,
genre text,
artist text,
japan character(4)
);
SQL;
	execSQL($sql);
	/*
	$dbData = array();
	for($i = 1; $i < $num; $i++){
		$line = $list[$i];
		$dd = explode("\t", $line);
		$dnum = count($dd);
		$lineData = "";
		for($j = 0; $j < $dnum; $j++){
			$d = $dd[$j];
			$d = addslashes($d);
			if($j == 0){
				$lineData .= "'".$d."',";
			}
			else if(strval($d) === strval(abs(intval($d)))){
				$lineData .= $d . ",";
			}
			else{
				$lineData .= "'".$d."',";
			}
			
			if($j == $dnum -1){
				$lineData = rtrim($lineData, ",");
			}
		}
		array_push($dbData, $lineData);
	}*/
	execCopy('temp', $list);
}

/**
 * SQL実行
 * @return ary
 */
function execSQL($SQL){
	$conDB = pg_connect(DB_PASS);
	pg_query($conDB, 'SET standard_conforming_strings = FALSE');
	$resObj = pg_query($conDB, $SQL);
}

/**
 * PGSQLでcopyを実行
 * @return ary
 */
function execCopy($table, $data){
	$conDB = pg_connect(DB_PASS);
	pg_query($conDB, 'SET standard_conforming_strings = FALSE');
	pg_copy_from($conDB, $table, $data);
	echo "copyExec";
}


/**
 * splitExec
 */
 function execSplit(){
 	$EXEC_SQL = <<<SQL

DROP TABLE IF EXISTS iidxsd_origin cascade;
CREATE TABLE iidxsd_origin as (
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,spn as dif, n7 as notes,japan,abs(11) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,dpn as dif, n14 as notes,japan,abs(21) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,sph as dif, h7 as notes,japan,abs(12) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,dph as dif, h14 as notes,japan,abs(22) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,spa as dif, a7 as notes,japan,abs(13) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,dpa as dif, a14 as notes,japan,abs(23) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,spd as dif, d7 as notes,japan,abs(14) as dif_type FROM temp 
UNION
SELECT no,msc,(CASE WHEN ver < 50 THEN ver ELSE (ver - 50) END) as ver_no,(CASE WHEN ver < 50 THEN 1 ELSE 2 END) as ver_type,bpm,bpmn,bpmx,(CASE WHEN bpmd = '' OR bpmd IS NULL THEN '\N' ELSE bpmd END) as bpmd,artist,genre,dpd as dif, d14 as notes,japan,abs(24) as dif_type FROM temp 
ORDER BY dif,japan,msc,dif_type);














-- 譜面詳細データ
DROP TABLE IF EXISTS  data_detail cascade;

create table data_detail (
  data_detail_id serial not null
  , playtime integer
  , bpm_detail text
  , constraint data_detail_PKC primary key (data_detail_id)
) ;

-- 収録状況テーブル
DROP TABLE IF EXISTS  list cascade;

create table list (
  list_id serial not null
  , ver_id integer
  , data_id integer
  , secret_id integer
  , info text
  , constraint list_PKC primary key (list_id,ver_id,data_id,secret_id)
) ;

-- 譜面データテーブル
drop table notesdata cascade;

create table notesdata (
  data_id serial not null
  , music_id integer
  , dif_id integer
  , genre_id integer
  , artist_id integer
  , data_detail_id integer
  , dif integer
  , bpm_min integer
  , bpm_max integer
  , notes integer
  , scratch integer
  , chargenotes integer
  , bss integer
  , data_date date
  , constraint notesdata_PKC primary key (data_id,music_id,dif_id,genre_id,artist_id,data_detail_id)
) ;


-- バージョンテーブル
DROP TABLE IF EXISTS  version cascade;

create table version (
  ver_id serial not null
  , ver_no integer
  , ver_type integer
  , ver_disc integer
  , ver_name text
  , ver_name_short text
  , ver_date date
  , constraint version_PKC primary key (ver_id)
) ;

-- 難易度名テーブル
DROP TABLE IF EXISTS  diftype cascade;

create table diftype (
  dif_id serial not null
  , dif_type text
  , constraint diftype_PKC primary key (dif_id)
) ;

-- ジャンル名テーブル
DROP TABLE IF EXISTS  genre cascade;

create table genre (
  genre_id serial not null
  , genre_name text
  , constraint genre_PKC primary key (genre_id)
) ;

-- アーティスト名義テーブル
DROP TABLE IF EXISTS  artist cascade;

create table artist (
  artist_id serial not null
  , artist_name text
  , constraint artist_PKC primary key (artist_id)
) ;

-- 曲情報テーブル
DROP TABLE IF EXISTS  music cascade;

create table music (
  music_id serial not null
  , music_no integer
  , music_name text
  , music_name_sort text
  , music_read text
  , music_battle integer
  , music_first_ver integer
  , music_folder_ver integer
  , constraint music_PKC primary key (music_id)
) ;

comment on table data_detail is '譜面詳細データ';
comment on column data_detail.data_detail_id is '譜面詳細ID';
comment on column data_detail.playtime is '演奏時間';
comment on column data_detail.bpm_detail is '詳細BPM';

comment on table list is '収録状況テーブル';
comment on column list.list_id is 'リストID';
comment on column list.ver_id is 'バージョンID';
comment on column list.data_id is 'データID';
comment on column list.secret_id is 'シークレット';
comment on column list.info is '情報';

comment on table notesdata is '譜面データテーブル';
comment on column notesdata.data_id is 'データID';
comment on column notesdata.music_id is '曲ID';
comment on column notesdata.dif_id is '難度タイプID';
comment on column notesdata.genre_id is 'ジャンルID';
comment on column notesdata.artist_id is 'アーティスト名義ID';
comment on column notesdata.data_detail_id is '譜面詳細ID';
comment on column notesdata.dif is '難易度';
comment on column notesdata.bpm_min is '最小BPM';
comment on column notesdata.bpm_max is '最大BPM';
comment on column notesdata.notes is 'ノート数';
comment on column notesdata.scratch is 'スクラッチ';
comment on column notesdata.chargenotes is 'チャージノート';
comment on column notesdata.bss is 'バックスピンスクラッチ';
comment on column notesdata.data_date is 'データ日付';

comment on table version is 'バージョンテーブル';
comment on column version.ver_id is 'バージョンID';
comment on column version.ver_no is 'バージョンno';
comment on column version.ver_type is 'バージョンタイプ';
comment on column version.ver_disc is 'ディスク';
comment on column version.ver_name is '名前';
comment on column version.ver_name_short is '短縮名';
comment on column version.ver_date is '日付';

comment on table diftype is '難易度名テーブル';
comment on column diftype.dif_id is '難易度タイプID';
comment on column diftype.dif_type is '難易度名';

comment on table genre is 'ジャンル名テーブル';
comment on column genre.genre_id is 'ジャンル名ID';
comment on column genre.genre_name is 'ジャンル名';

comment on table artist is 'アーティスト名義テーブル';
comment on column artist.artist_id is 'アーティスト名義ID';
comment on column artist.artist_name is 'アーティスト名';

comment on table music is '曲情報テーブル';
comment on column music.music_id is '曲ID';
comment on column music.music_no is '曲no';
comment on column music.music_name is '曲名';
comment on column music.music_name_sort is 'ソート用曲名';
comment on column music.music_read is '読み方';
comment on column music.music_battle is '別難度Battle可否';
comment on column music.music_first_ver is '初登場version';
comment on column music.music_folder_ver is '収録フォルダ';




INSERT INTO genre 
SELECT nextval('genre_genre_id_seq') as genre_id,iidxsd_origin.genre as genre_name 
FROM iidxsd_origin GROUP BY iidxsd_origin.genre ORDER BY iidxsd_origin.genre;


INSERT INTO artist 
SELECT nextval('artist_artist_id_seq') as artist_id,iidxsd_origin.artist as artist_name 
FROM iidxsd_origin GROUP BY iidxsd_origin.artist ORDER BY iidxsd_origin.artist;



INSERT INTO data_detail 
SELECT nextval('data_detail_data_detail_id_seq') as data_detail_id,0 as time, 
iidxsd_origin.bpmd
FROM iidxsd_origin GROUP BY iidxsd_origin.bpmd ORDER BY iidxsd_origin.bpmd;


INSERT INTO music 
SELECT nextval('music_music_id_seq') as music_id,
currval('music_music_id_seq') as music_no,
msc as music_name,
UPPER(msc) as music_name_sort,
'' as music_read,
1 as music_battle,
MIN(ver_no) as music_first_ver,
MIN(
  CASE WHEN ver_type = 2 THEN (ver_no + 50) ELSE ver_no END
) as music_folder_ver 
FROM iidxsd_origin 
GROUP BY msc ORDER BY msc;


-- バージョンテーブル
DROP TABLE IF EXISTS  version cascade;

create table version (
  ver_id serial not null
  , ver_no integer
  , ver_type integer
  , ver_disc integer
  , ver_name text
  , ver_name_short text
  , ver_date date
  , constraint version_PKC primary key (ver_id)
) ;

-- 難易度名テーブル
DROP TABLE IF EXISTS  diftype cascade;

create table diftype (
  dif_id serial not null
  , dif_type text
  , constraint diftype_PKC primary key (dif_id)
) ;

comment on table version is 'バージョンテーブル';
comment on column version.ver_id is 'バージョンID';
comment on column version.ver_no is 'バージョンno';
comment on column version.ver_type is 'バージョンタイプ';
comment on column version.ver_disc is 'ディスク';
comment on column version.ver_name is '名前';
comment on column version.ver_name_short is '短縮名';
comment on column version.ver_date is '日付';

comment on table diftype is '難易度名テーブル';
comment on column diftype.dif_id is '難易度タイプID';
comment on column diftype.dif_type is '難易度名';



--難易度テーブル挿入

INSERT INTO diftype VALUES (11,'SINGLE PLAY NORMAL');
INSERT INTO diftype VALUES (12,'SINGLE PLAY HYPER');
INSERT INTO diftype VALUES (13,'SINGLE PLAY ANOTHER');
INSERT INTO diftype VALUES (14,'SINGLE PLAY DARK ANOTHER');
INSERT INTO diftype VALUES (21,'DOUBLE PLAY NORMAL');
INSERT INTO diftype VALUES (22,'DOUBLE PLAY HYPER');
INSERT INTO diftype VALUES (23,'DOUBLE PLAY ANOTHER');
INSERT INTO diftype VALUES (24,'DOUBLE PLAY DARK ANOTHER');


--バージョンテーブル挿入

INSERT INTO version VALUES (nextval('version_ver_id_seq'),0,1,1,'1st style','1st','1999/02/26');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),1,1,1,'substream','ss','1999/07/27');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),2,1,1,'2nd style','2nd','1999/09/30');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),3,1,1,'3rd style','3rd','2000/02/25');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),4,1,1,'4th style','4th','2000/09/28');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),3,2,1,'3rd style','3rd','2000/11/02');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),5,1,1,'5th style','5th','2001/03/27');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),4,2,1,'4th style','4th','2001/03/29');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),5,2,1,'5th style','5th','2001/08/30');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),6,1,1,'6th style','6th','2001/09/28');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),7,1,1,'7th style','7th','2002/03/27');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),6,2,1,'6th style','6th','2002/07/18');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),8,1,1,'8th style','8th','2002/09/27');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),9,1,1,'9th style','9th','2003/06/25');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),10,1,1,'10th style','10th','2004/02/18');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),7,2,1,'7th style','7th','2004/05/13');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),11,1,1,'11 IIDX RED','RED','2004/10/28');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),8,2,1,'8th style','8th','2004/11/18');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),9,2,1,'9th style','9th','2005/03/24');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),12,1,1,'12 HAPPY SKY','HSK','2005/07/13');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),10,2,1,'10th style','10th','2005/11/17');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),13,1,1,'13 DistorteD','DD','2006/03/15');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),11,2,1,'11 IIDX RED','RED','2006/05/18');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),12,2,1,'12 HAPPY SKY','HSK','2006/12/14');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),14,1,1,'14 GOLD','GOLD','2007/02/21');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),13,2,1,'13 DistorteD','DD','2007/08/30');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),15,1,1,'15 DJ TROOPERS','DJT','2007/12/19');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),14,2,1,'14 GOLD','GOLD','2008/05/29');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),16,1,1,'16 EMPRESS','EMP','2008/11/19');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),15,2,1,'15 DJ TROOPERS','DJT','2008/12/18');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),16,2,1,'16 EMPRESS','EMP','2009/10/15');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),16,2,2,'PREMIUM BEST','PB','2009/10/15');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),17,1,1,'17 SIRIUS','SIR','2009/10/21');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),18,1,1,'18 Resort Anthem','RA','2010/09/15');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),19,1,1,'19 Lincle','LC','2011/09/15');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),20,1,1,'20 tricoro','TCR','2012/09/19');
INSERT INTO version VALUES (nextval('version_ver_id_seq'),21,1,1,'21 SPADA','SPA','2013/11/13');


INSERT INTO notesdata (SELECT nextval('notesdata_data_id_seq') as data_id, music_id, dif_type as dif_id, genre_id, artist_id, data_detail_id, dif, bpmn as bpm_min, bpmx as bpm_max, notes, 0 as scratch, 0 as chargenotes, 0 as bss, MIN(ver_date) as data_date
FROM 
(((((iidxsd_origin JOIN music ON iidxsd_origin.msc = music_name) 
 JOIN genre ON iidxsd_origin.genre = genre_name)
 JOIN artist ON iidxsd_origin.artist = artist.artist_name)
 JOIN version ON iidxsd_origin.ver_no = version.ver_no AND iidxsd_origin.ver_type = version.ver_type)
 JOIN data_detail ON iidxsd_origin.bpmd = data_detail.bpm_detail AND data_detail.bpm_detail IS NOT NULL)
GROUP BY 
  music_id
  , dif_id
  , genre_id
  , artist_id
  , data_detail_id
  , dif
  , dif_type
  , bpm_min
  , bpm_max
  , notes
HAVING notes > 0);

DROP TABLE IF EXISTS iidxsd_origin_x;
CREATE TABLE iidxsd_origin_x  AS SELECT ver_no, ver_type, music_id, dif_type as dif_id, genre_id, artist_id, data_detail_id, dif, bpmn as bpm_min, bpmx as bpm_max, notes, 0 as chargenotes, 0 as bss
FROM 
((((iidxsd_origin JOIN music ON iidxsd_origin.msc = music_name) 
 JOIN genre ON iidxsd_origin.genre = genre_name)
 JOIN artist ON iidxsd_origin.artist = artist.artist_name)
 JOIN data_detail ON iidxsd_origin.bpmd = data_detail.bpm_detail)
;


INSERT INTO list (
SELECT nextval('list_list_id_seq') as list_id, 
ver_id, data_id, 0 as secret_id, '' as info 
FROM 
((iidxsd_origin_x
 JOIN version
USING(ver_no, ver_type) )
 JOIN notesdata
USING(dif, notes, bpm_min, bpm_max, music_id, genre_id, artist_id, data_detail_id))
GROUP BY ver_id, data_id
);


DROP VIEW IF EXISTS ac0_core; DROP VIEW IF EXISTS ac0;
CREATE VIEW ac0 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 1;

DROP VIEW IF EXISTS ac1_core; DROP VIEW IF EXISTS ac1;
CREATE VIEW ac1 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 2;

DROP VIEW IF EXISTS ac2_core; DROP VIEW IF EXISTS ac2;
CREATE VIEW ac2 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 3;

DROP VIEW IF EXISTS ac3_core; DROP VIEW IF EXISTS ac3;
CREATE VIEW ac3 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 4;

DROP VIEW IF EXISTS ac4_core; DROP VIEW IF EXISTS ac4;
CREATE VIEW ac4 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 5;

DROP VIEW IF EXISTS cs3_core; DROP VIEW IF EXISTS cs3;
CREATE VIEW cs3 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 6;

DROP VIEW IF EXISTS ac5_core; DROP VIEW IF EXISTS ac5;
CREATE VIEW ac5 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 7;

DROP VIEW IF EXISTS cs4_core; DROP VIEW IF EXISTS cs4;
CREATE VIEW cs4 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 8;

DROP VIEW IF EXISTS cs5_core; DROP VIEW IF EXISTS cs5;
CREATE VIEW cs5 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 9;

DROP VIEW IF EXISTS ac6_core; DROP VIEW IF EXISTS ac6;
CREATE VIEW ac6 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 10;

DROP VIEW IF EXISTS ac7_core; DROP VIEW IF EXISTS ac7;
CREATE VIEW ac7 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 11;

DROP VIEW IF EXISTS cs6_core; DROP VIEW IF EXISTS cs6;
CREATE VIEW cs6 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 12;

DROP VIEW IF EXISTS ac8_core; DROP VIEW IF EXISTS ac8;
CREATE VIEW ac8 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 13;

DROP VIEW IF EXISTS ac9_core; DROP VIEW IF EXISTS ac9;
CREATE VIEW ac9 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 14;

DROP VIEW IF EXISTS ac10_core; DROP VIEW IF EXISTS ac10;
CREATE VIEW ac10 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 15;

DROP VIEW IF EXISTS cs7_core; DROP VIEW IF EXISTS cs7;
CREATE VIEW cs7 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 16;

DROP VIEW IF EXISTS ac11_core; DROP VIEW IF EXISTS ac11;
CREATE VIEW ac11 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 17;

DROP VIEW IF EXISTS cs8_core; DROP VIEW IF EXISTS cs8;
CREATE VIEW cs8 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 18;

DROP VIEW IF EXISTS cs9_core; DROP VIEW IF EXISTS cs9;
CREATE VIEW cs9 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 19;

DROP VIEW IF EXISTS ac12_core; DROP VIEW IF EXISTS ac12;
CREATE VIEW ac12 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 20;

DROP VIEW IF EXISTS cs10_core; DROP VIEW IF EXISTS cs10;
CREATE VIEW cs10 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 21;

DROP VIEW IF EXISTS ac13_core; DROP VIEW IF EXISTS ac13;
CREATE VIEW ac13 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 22;

DROP VIEW IF EXISTS cs11_core; DROP VIEW IF EXISTS cs11;
CREATE VIEW cs11 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 23;

DROP VIEW IF EXISTS cs12_core; DROP VIEW IF EXISTS cs12;
CREATE VIEW cs12 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 24;

DROP VIEW IF EXISTS ac14_core; DROP VIEW IF EXISTS ac14;
CREATE VIEW ac14 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 25;

DROP VIEW IF EXISTS cs13_core; DROP VIEW IF EXISTS cs13;
CREATE VIEW cs13 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 26;

DROP VIEW IF EXISTS ac15_core; DROP VIEW IF EXISTS ac15;
CREATE VIEW ac15 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 27;

DROP VIEW IF EXISTS cs14_core; DROP VIEW IF EXISTS cs14;
CREATE VIEW cs14 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 28;

DROP VIEW IF EXISTS ac16_core; DROP VIEW IF EXISTS ac16;
CREATE VIEW ac16 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 29;

DROP VIEW IF EXISTS cs15_core; DROP VIEW IF EXISTS cs15;
CREATE VIEW cs15 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 30;

DROP VIEW IF EXISTS cs16_core; DROP VIEW IF EXISTS cs16;
CREATE VIEW cs16 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 31;

DROP VIEW IF EXISTS cs16_2_core; DROP VIEW IF EXISTS cs16_2;
CREATE VIEW cs16_2 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 32;

DROP VIEW IF EXISTS ac17_core; DROP VIEW IF EXISTS ac17;
CREATE VIEW ac17 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 33;

DROP VIEW IF EXISTS ac18_core; DROP VIEW IF EXISTS ac18;
CREATE VIEW ac18 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 34;

DROP VIEW IF EXISTS ac19_core; DROP VIEW IF EXISTS ac19;
CREATE VIEW ac19 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 35;

DROP VIEW IF EXISTS ac20_core; DROP VIEW IF EXISTS ac20;
CREATE VIEW ac20 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 36;

DROP VIEW IF EXISTS ac21_core; DROP VIEW IF EXISTS ac21;
CREATE VIEW ac21 AS SELECT list.info, ver_id, music.music_id, music.music_no, music.music_name, music.music_name_sort, music.music_battle, music.music_first_ver, music.music_folder_ver, genre.genre_id, genre.genre_name, artist.artist_id, artist.artist_name, notesdata.data_id, notesdata.dif, notesdata.dif_id, notesdata.bpm_min, notesdata.bpm_max, notesdata.notes, notesdata.scratch, notesdata.chargenotes, notesdata.bss FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id) WHERE list.ver_id = 37;


DROP VIEW IF EXISTS alldata;
CREATE VIEW alldata AS 
SELECT music_id, music_no, music_name, music_name_sort, music_battle, music_folder_ver, 
genre_id, genre_name, artist_id, artist_name, data_id, dif, dif_id, bpm_min, bpm_max, 
notes, scratch, chargenotes, bss, data_date
FROM (notesdata JOIN music USING(music_id) JOIN artist USING(artist_id) JOIN genre USING(genre_id))  
SQL;
execSQL($EXEC_SQL);
echo "data splited";
}