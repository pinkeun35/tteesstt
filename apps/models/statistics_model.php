<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_statistics_energy_schedule($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			SUM(C.data1) AS data1,
			SUM(C.data2) AS data2,
			SUM(C.data3) AS data3,
			SUM(C.data4) AS data4,
			SUM(C.data5) AS data5
		FROM	(
			SELECT
				(B.data1 * A.life) AS data1,
				(B.data2 * A.life) AS data2,
				(B.data3 * A.life) AS data3,
				(B.data4 * A.life) AS data4,
				(B.data5 * A.life) AS data5
			FROM building AS A INNER JOIN data_schedule AS B ON B.build_seq = A.seq
			WHERE A.project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND A.zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND A.zone_seq = ?";
			$sql .= " AND A.seq = ?";
		}
		$sql .= ") AS C";

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_energy_schedule_period($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			SUM(data1) AS data1,
			SUM(data2) AS data2,
			SUM(data3) AS data3,
			SUM(data4) AS data4,
			SUM(data5) AS data5
		FROM data_schedule
		WHERE project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND seq = ?";
		}

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_data_preuse($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			SUM(pre_use) AS pre_use,
			SUM(`use`) AS data_use,
			SUM(post_use) AS post_use
		FROM	data_preuse
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		
		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_data_occurrence($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
					SUM(pre_use) AS data_use
		FROM	data_occurrence
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_data_occurrence_period($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
					byear AS dyear,
					SUM(pre_use) AS data_use
		FROM	data_occurrence
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= "
		GROUP BY byear";
		
		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$rows = $query->result();
		
		return $rows;
	}

	function get_statistics_data_occurrence_minmaxyear($project, $zone, $build) {
		$sql = "SELECT A.dyear, CASE A.year_min WHEN 0 THEN YEAR(NOW()) ELSE A.year_min END AS year_min, CASE A.year_max WHEN 0 THEN YEAR(NOW()) ELSE A.year_max END AS year_max FROM ( ";
		$sql .= "SELECT
			byear AS dyear,
			MIN(byear) AS year_min,
			MAX(byear) AS year_max
		FROM	data_occurrence
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= "
		GROUP BY byear";
		$sql .= " ) AS A";

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_data_occurrence_projectall() {
		$sql = "";
		$sql .= "SELECT
			B.project,
			A.prjname AS project_name,
			B.dyear,
			B.data_use
		FROM	project AS A INNER JOIN (
			SELECT
				byear AS dyear,
				project_seq AS project,
				SUM(pre_use) AS data_use
			FROM	data_occurrence
			GROUP BY project_seq, byear
			ORDER BY project_seq ASC, byear ASC
		) AS B ON B.project = A.seq";

		$query = $this->db->query($sql);
		$rows = $query->result();
		
		return $rows;
	}

	function get_statistics_data_useinfo($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			SUM(A.data1) AS data1,
			SUM(A.data2) AS data2,
			SUM(A.data3) AS data3,
			SUM(A.data4) AS data4,
			SUM(A.data5) AS data5,
			SUM(A.data6) AS data6
		FROM	(
			SELECT
				( data1 * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '전기'), 1) ) AS data1,
				( data2 * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '상수도'), 1) ) AS data2,
				( data3 * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '경유'), 1) ) AS data3,
				( data4 * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '도시가스'), 1) ) AS data4,
				( data5 * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = 'LPG'), 1) ) AS data5,
				( data6 * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '펠릿'), 1) ) AS data6
			FROM	data_useinfo
			WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= ") AS A";
		
		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_data_useinfo_period($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			use_year AS dyear,
			( SUM(data1) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '전기'), 1) ) AS data1,
			( SUM(data2) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '상수도'), 1) ) AS data2,
			( SUM(data3) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '경유'), 1) ) AS data3,
			( SUM(data4) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '도시가스'), 1) ) AS data4,
			( SUM(data5) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = 'LPG'), 1) ) AS data5,
			( SUM(data6) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '펠릿'), 1) ) AS data6
		FROM	data_useinfo
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= "
		GROUP BY use_year";
		
		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$rows = $query->result();
		
		return $rows;
	}

	function get_statistics_data_useinfo_periodyear($project, $zone, $build, $year) {
		$sql = "";
		$sql .= "SELECT
			use_month AS dmonth,
			( SUM(data1) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '전기'), 1) ) AS data1,
			( SUM(data2) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '상수도'), 1) ) AS data2,
			( SUM(data3) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '경유'), 1) ) AS data3,
			( SUM(data4) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '도시가스'), 1) ) AS data4,
			( SUM(data5) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = 'LPG'), 1) ) AS data5,
			( SUM(data6) * IFNULL((SELECT eco2 FROM lci_energy WHERE ename = '펠릿'), 1) ) AS data6
		FROM	data_useinfo
		WHERE	use_year = ?
		AND	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= "
		GROUP BY use_month";
		
		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, array($year, $project));
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($year, $project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($year, $project, $zone, $build));
		}
		$rows = $query->result();
		
		return $rows;
	}

	function get_statistics_data_maintenance($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			SUM(post_use) AS data_use
		FROM	data_maintenance
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_data_maintenance_period($project, $zone, $build) {
		$sql = "";
		$sql .= "SELECT
			uyear AS dyear,
			SUM(post_use) AS data_use
		FROM	data_maintenance
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= "
		GROUP BY uyear";

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$rows = $query->result();
		
		return $rows;
	}

	function get_statistics_data_maintenance_projectall() {
		$sql = "";
		$sql .= "SELECT
			B.project,
			A.prjname,
			B.dyear,
			B.data_use
		FROM	project AS A INNER JOIN (
			SELECT
				uyear AS dyear,
				project_seq AS project,
				SUM(post_use) AS data_use
			FROM	data_maintenance
			GROUP BY project_seq, uyear
			ORDER BY project_seq ASC, uyear ASC
		) AS B ON B.project = A.seq";

		$query = $this->db->query($sql);
		$rows = $query->result();
		
		return $rows;
	}

	function get_statistics_data_maintenance_minmaxyear($project, $zone, $build) {
		$sql = "SELECT A.dyear, CASE A.year_min WHEN 0 THEN YEAR(NOW()) ELSE A.year_min END AS year_min, CASE A.year_max WHEN 0 THEN YEAR(NOW()) ELSE A.year_max END AS year_max FROM ( ";
		$sql .= "SELECT
			uyear AS dyear,
			MIN(uyear) AS year_min,
			MAX(uyear) AS year_max
		FROM	data_maintenance
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND build_seq = ?";
		}
		$sql .= "
		GROUP BY uyear
		) AS A";

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_statistics_building_minmaxyear($project, $zone, $build) {
		$sql = "SELECT	CASE A.year_min WHEN 0 THEN YEAR(NOW()) ELSE A.year_min END AS year_min, CASE A.year_max WHEN 0 THEN YEAR(NOW()) ELSE A.year_max END AS year_max FROM ( ";
		$sql .= "SELECT
			MIN(YEAR(date_end)) AS year_min,
			MAX(YEAR(date_end) + life) AS year_max
		FROM	building
		WHERE	project_seq = ?";
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ?";
			$sql .= " AND seq = ?";
		}
		$sql .= " ) AS A";

		if ($zone == 0 && $build == 0) {
			$query = $this->db->query($sql, $project);
		} else if ($zone != 0 && $build == 0) {
			$query = $this->db->query($sql, array($project, $zone));
		} else if ($zone != 0 && $build != 0) {
			$query = $this->db->query($sql, array($project, $zone, $build));
		}
		$row = $query->row(0);
		
		return $row;
	}

	function get_build_year($project, $zone, $build) {
		$sql = "SELECT	CASE A.year_min WHEN 0 THEN YEAR(NOW()) ELSE A.year_min END AS year_min, CASE A.year_max WHEN 0 THEN YEAR(NOW()) ELSE A.year_max END AS year_max FROM ( ";
		$sql .= "SELECT
			MIN(YEAR(date_end)) AS year_min,
			MAX(YEAR(date_end) + life) AS year_max
		FROM	building
		WHERE	project_seq = ?
		AND zone_seq = ?
		AND seq = ?";
		$sql .= " ) AS A";
		$query = $this->db->query($sql, array($project, $zone, $build));

		$row = $query->row(0);
		
		return $row;
	}
}
?>