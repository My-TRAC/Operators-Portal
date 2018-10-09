package models

//import "github.com/jinzhu/gorm"


type Sample struct {
	//gorm.Model  `valid:"-"`
	ID	        string  `valid:"-"`
	Modified   string  `valid:"-"`
	NumRatedActivities       int64 `valid:"-"`
	BestRatedActivity		 int64 `valid:"-"`
	BestRating				float64 `valid:"-"`
}

func (Sample) TableName() string{
	return "CigoJdbcActivitiesSummary"
}
func (sample *Sample) Insert() error {
	return DB.Create(&sample).Error
}

func (sample *Sample) Update() error {
	return DB.Save(&sample).Error
}

func (sample *Sample) Delete() error {
	return DB.Delete(&sample).Error
}

func FindSampleByID(id uint) (Sample, error) {
	var sample Sample
	err := DB.Unscoped().Where("id", id).Find(&sample).Error
	return sample, err
}

func FindSamples() ([]Sample, error) {
	var samples []Sample
	err := DB.Unscoped().Find(&samples).Error
	return samples, err
}

func (sample *Sample) ExistsSample() (bool, error) {
	c := 0
	err := DB.Model(&Sample{}).Unscoped().Where("id = ?", sample.ID).Count(&c).Error

	return c > 0, err
}
