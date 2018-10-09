package models

import "github.com/jinzhu/gorm"

type Sample struct {
	gorm.Model  `valid:"-"`
	Code        string  `valid:"-"`
	Description string  `valid:"-"`
	Value       float64 `valid:"-"`
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
	err := DB.Where("id", id).Find(&sample).Error
	return sample, err
}

func FindSamples() ([]Sample, error) {
	var samples []Sample
	err := DB.Find(&samples).Error
	return samples, err
}

func (sample *Sample) ExistsSample() (bool, error) {
	c := 0
	err := DB.Model(&Sample{}).Where("code = ?", sample.Code).Count(&c).Error

	return c > 0, err
}
