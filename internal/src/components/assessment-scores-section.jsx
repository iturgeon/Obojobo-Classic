import './assessment-scores-section.scss'
import React, { useState } from 'react'
import PropTypes from 'prop-types'
import SectionHeader from './section-header'
import AssessmentScoresSummary from './assessment-scores-summary'
import DataGridAssessmentScores from './data-grid-assessment-scores'
import Button from './button'
import SearchField from './search-field'

export default function AssessmentScoresSection({
	assessmentScores,
	selectedStudentIndex,
	onClickRefresh,
	onClickSetAdditionalAttempt,
	onClickScoreDetails,
	onClickDownloadScores
}) {
	const [search, setSearch] = useState('')

	// filter any null scores
	const scores = React.useMemo(() => {
		if (!assessmentScores) return []
		return assessmentScores.map(assessment => assessment.score).filter(score => score !== null)
	}, [assessmentScores])

	const assessmentScoresDataGridData = React.useMemo(() => {
		if (!assessmentScores) return []
		if (search === '') return [...assessmentScores]

		return assessmentScores.filter(assessment => assessment.user.toLowerCase().indexOf(search) > -1)
	}, [assessmentScores, search])

	return (
		<div className="repository--assessment-scores-section">
			<SectionHeader label="Assessment Scores" />
			<div className="assessment-section-body">
				<div className="assessment-scores-summary">
					<AssessmentScoresSummary scores={scores} onClickRefresh={onClickRefresh} />
				</div>
				<hr className="section-divider" />
				<div className="assessment-score-search">
					<p className="title">Scores by student</p>
					<SearchField value={search} placeholder="Search for a name" onChange={setSearch} />
				</div>
				<DataGridAssessmentScores
					data={assessmentScoresDataGridData || null}
					selectedIndex={selectedStudentIndex}
					onClickSetAdditionalAttempt={onClickSetAdditionalAttempt}
					onClickScoreDetails={onClickScoreDetails}
				/>
				<div className="download-button">
					<Button
						onClick={onClickDownloadScores}
						type="small"
						text="Download these scores as a CSV file"
					/>
				</div>
			</div>
		</div>
	)
}

AssessmentScoresSection.propTypes = {
	assessmentScores: PropTypes.arrayOf(
		PropTypes.shape({
			user: PropTypes.string.isRequired,
			userID: PropTypes.string.isRequired,
			score: PropTypes.shape({
				value: PropTypes.number,
				isScoreImported: PropTypes.bool
			}).isRequired,
			lastSubmitted: PropTypes.number,
			attempts: PropTypes.shape({
				numAttemptsTaken: PropTypes.number.isRequired,
				additional: PropTypes.number.isRequired,
				numAttempts: PropTypes.number.isRequired,
				isAttemptInProgress: PropTypes.bool
			})
		})
	),
	selectedStudentIndex: PropTypes.number,
	onClickRefresh: PropTypes.func.isRequired,
	onClickDownloadScores: PropTypes.func.isRequired,
	onClickAddAdditionalAttempt: PropTypes.func.isRequired,
	onClickRemoveAdditionalAttempt: PropTypes.func.isRequired,
	onClickScoreDetails: PropTypes.func.isRequired
}
